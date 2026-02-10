/**
 * Build script for minifying the sponsor-ads embed script
 * Injects API URL from .env file
 */

import { minify } from 'terser';
import fs from 'fs';
import path from 'path';
import { fileURLToPath } from 'url';

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);
const rootDir = path.resolve(__dirname, '..');

const inputFile = path.join(rootDir, 'public/js/sponsor-ads.js');
const outputFile = path.join(rootDir, 'public/js/sponsor-ads.min.js');
const envFile = path.join(rootDir, '.env');

// Helper to load env variables
function loadEnv() {
    if (!fs.existsSync(envFile)) {
        console.warn('⚠️ No .env file found. Using default API URL.');
        return {};
    }

    const content = fs.readFileSync(envFile, 'utf8');
    const env = {};

    content.split('\n').forEach(line => {
        const match = line.match(/^\s*([\w_]+)\s*=\s*(.*)?\s*$/);
        if (match) {
            let value = match[2] || '';
            // Remove quotes if present
            if (value.startsWith('"') && value.endsWith('"')) {
                value = value.slice(1, -1);
            } else if (value.startsWith("'") && value.endsWith("'")) {
                value = value.slice(1, -1);
            }
            env[match[1]] = value;
        }
    });

    return env;
}

async function build() {
    console.log('🔨 Building embed script...');

    try {
        // Read the source file
        let code = fs.readFileSync(inputFile, 'utf8');

        // Load APP_URL from .env
        const env = loadEnv();
        const appUrl = env.APP_URL || 'http://127.0.0.1:8000';
        const apiUrl = `${appUrl}/api`;

        console.log(`📍 Injecting API URL: ${apiUrl}`);

        // Replace placeholder with actual API URL
        code = code.replace('__API_URL_PLACEHOLDER__', apiUrl);

        // Minify
        const result = await minify(code, {
            compress: {
                dead_code: true,
                drop_console: false, // Keep console for debugging
                drop_debugger: true,
            },
            mangle: {
                toplevel: true,
                reserved: ['SponsorAds'], // Don't mangle the global API
            },
            format: {
                comments: false,
                preamble: '/* SponsorAds Embed Script */',
            },
        });

        // Write minified file
        fs.writeFileSync(outputFile, result.code, 'utf8');

        // Calculate sizes
        const originalSize = fs.statSync(inputFile).size;
        const minifiedSize = fs.statSync(outputFile).size;
        const reduction = ((1 - minifiedSize / originalSize) * 100).toFixed(2);

        console.log('✅ Build complete!');
        console.log(`   Original: ${(originalSize / 1024).toFixed(2)} KB`);
        console.log(`   Minified: ${(minifiedSize / 1024).toFixed(2)} KB`);
        console.log(`   Reduction: ${reduction}%`);
        console.log(`   Output: ${outputFile}`);
    } catch (error) {
        console.error('❌ Build failed:', error);
        process.exit(1);
    }
}

build();
