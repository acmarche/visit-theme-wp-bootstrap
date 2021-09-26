
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
          virginie: '#F5CC73',
          mathieu: '#64966F',
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
