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
		extend: {
			opacity: ['disabled'],
			borderWidth: ['first', 'last'],
		},
	},
	plugins: [require('@tailwindcss/forms')],
};
