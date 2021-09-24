module.exports = {
	darkMode: false,
	purge: ['./index.html', './src/**/*.{js,ts,jsx,tsx}'],
	theme: {
		extend: {
			fontFamily: {
				sans: ['Lato', 'Helvetica', 'Arial', 'sans-serif'],
			},
		},
	},
	variants: {
		extend: {},
	},
	plugins: [require('@tailwindcss/forms')],
};