
module.exports = {
  mode: 'jit',
  purge: [
    './*.html',
  ],
  darkMode: false,
  theme: {
    extend: {
      fontFamily: {
        'signature': ['Georgia', 'serif','Helvetica', 'Arial', 'sans-serif'],
      },
      colors: {
        signature:  {
          heidi: '#AAB7D8',
        },
      },
    },
  },
  variants: {
    extend: {},
  },
  plugins: [],
}
