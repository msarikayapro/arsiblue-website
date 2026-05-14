/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './resources/**/*.blade.php',
        './resources/**/*.js',
        './app/View/Components/**/*.php',
    ],
    darkMode: 'class',
    theme: {
        extend: {
            colors: {
                // Mediterranean Serenity — Material 3 token sistemi
                // (stitch-designs/_design-system/DESIGN.md ile uyumlu)
                primary: '#005d90',
                'on-primary': '#ffffff',
                'primary-container': '#0077b6',
                'on-primary-container': '#f3f7ff',
                'primary-fixed': '#cde5ff',
                'primary-fixed-dim': '#94ccff',
                'on-primary-fixed': '#001d32',
                'on-primary-fixed-variant': '#004b74',
                'inverse-primary': '#94ccff',

                secondary: '#00677d',
                'on-secondary': '#ffffff',
                'secondary-container': '#50d9fe',
                'on-secondary-container': '#005c70',
                'secondary-fixed': '#b3ebff',
                'secondary-fixed-dim': '#4cd6fb',
                'on-secondary-fixed': '#001f27',
                'on-secondary-fixed-variant': '#004e5f',

                tertiary: '#864a00',
                'on-tertiary': '#ffffff',
                'tertiary-container': '#a95f00',
                'on-tertiary-container': '#fff6f1',
                'tertiary-fixed': '#ffdcc0',
                'tertiary-fixed-dim': '#ffb877',
                'on-tertiary-fixed': '#2e1600',
                'on-tertiary-fixed-variant': '#6c3a00',

                error: '#ba1a1a',
                'on-error': '#ffffff',
                'error-container': '#ffdad6',
                'on-error-container': '#93000a',

                background: '#f7f9ff',
                'on-background': '#181c20',
                surface: '#f7f9ff',
                'on-surface': '#181c20',
                'surface-dim': '#d7dae0',
                'surface-bright': '#f7f9ff',
                'surface-container-lowest': '#ffffff',
                'surface-container-low': '#f1f4f9',
                'surface-container': '#ebeef4',
                'surface-container-high': '#e6e8ee',
                'surface-container-highest': '#e0e2e8',
                'on-surface-variant': '#404850',
                'inverse-surface': '#2d3135',
                'inverse-on-surface': '#eef1f6',
                'surface-variant': '#e0e2e8',
                'surface-tint': '#006399',

                outline: '#707881',
                'outline-variant': '#bfc7d1',

                // Brand ek renkleri (spec § 18 + DESIGN.md "Sand and Sea")
                sand: '#FAF3E0',
                navy: '#0F172A',
                'accent-yellow': '#FFB703',
                'accent-orange': '#FB8500',
                whatsapp: '#10B981',
            },
            fontFamily: {
                sans: ['"Plus Jakarta Sans"', 'system-ui', 'sans-serif'],
                'display-lg': ['"Plus Jakarta Sans"'],
                'headline-lg': ['"Plus Jakarta Sans"'],
                'headline-lg-mobile': ['"Plus Jakarta Sans"'],
                'headline-md': ['"Plus Jakarta Sans"'],
                'body-lg': ['"Plus Jakarta Sans"'],
                'body-md': ['"Plus Jakarta Sans"'],
                'label-md': ['"Plus Jakarta Sans"'],
            },
            fontSize: {
                'display-lg': ['48px', { lineHeight: '1.1', letterSpacing: '-0.02em', fontWeight: '700' }],
                'headline-lg': ['32px', { lineHeight: '1.2', fontWeight: '700' }],
                'headline-lg-mobile': ['28px', { lineHeight: '1.2', fontWeight: '700' }],
                'headline-md': ['24px', { lineHeight: '1.3', fontWeight: '600' }],
                'body-lg': ['18px', { lineHeight: '1.6', fontWeight: '400' }],
                'body-md': ['16px', { lineHeight: '1.5', fontWeight: '400' }],
                'label-md': ['14px', { lineHeight: '1.4', fontWeight: '600' }],
            },
            spacing: {
                'margin-mobile': '16px',
                'gutter': '24px',
                'section-gap-mobile': '48px',
                'section-gap-desktop': '80px',
            },
            maxWidth: {
                'container-max-width': '1200px',
            },
            borderRadius: {
                DEFAULT: '0.25rem',
                lg: '0.5rem',
                xl: '0.75rem',
                full: '9999px',
            },
            animation: {
                'pulse-slow': 'pulse 3s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                'float': 'float 6s ease-in-out infinite',
            },
            keyframes: {
                float: {
                    '0%, 100%': { transform: 'translateY(0)' },
                    '50%': { transform: 'translateY(-10px)' },
                },
            },
        },
    },
    plugins: [
        require('@tailwindcss/forms'),
        require('@tailwindcss/typography'),
    ],
};
