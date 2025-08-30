/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
    "./app/**/*.php",
    "./config/**/*.php",
  ],
  theme: {
    extend: {
      colors: {
        primary: {
          DEFAULT: '#18375d',
          dark: '#122a47',
          light: '#2a5a8a',
        },
        secondary: {
          DEFAULT: '#858796',
          dark: '#6b6d7d',
          light: '#d1d3e2',
        },
        success: {
          DEFAULT: '#1cc88a',
          dark: '#17a673',
          light: '#bff0de',
        },
        warning: {
          DEFAULT: '#f6c23e',
          dark: '#f4b30d',
          light: '#fceec9',
        },
        danger: {
          DEFAULT: '#e74a3b',
          dark: '#e02d1b',
          light: '#f8ccc8',
        },
        info: {
          DEFAULT: '#36b9cc',
          dark: '#2c9faf',
          light: '#c7ebf1',
        },
        light: '#f6f4e8',
        dark: '#5a5c69',
        gray: {
          50: '#f6f4e8',
          100: '#f0ede0',
          200: '#e8e4d4',
          300: '#d1d5db',
          400: '#9ca3af',
          500: '#6b7280',
          600: '#4b5563',
          700: '#374151',
          800: '#1f2937',
          900: '#111827',
        },
        border: '#e8e4d4',
        'text-muted': '#6b7280',
      },
      fontFamily: {
        sans: ['Inter', 'ui-sans-serif', 'system-ui', 'sans-serif'],
      },
      boxShadow: {
        'custom': '0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15)',
        'custom-lg': '0 1rem 3rem rgba(0, 0, 0, 0.175)',
      },
      animation: {
        'fade-in': 'fadeIn 0.5s ease-in-out',
      },
      keyframes: {
        fadeIn: {
          '0%': { opacity: '0', transform: 'translateY(10px)' },
          '100%': { opacity: '1', transform: 'translateY(0)' },
        }
      }
    },
  },
  plugins: [],
}
