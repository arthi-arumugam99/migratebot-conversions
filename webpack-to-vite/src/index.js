// Test import of a JavaScript module
// MIGRATED: @/ alias path — ensure vite.config.ts has resolve.alias: { '@': path.resolve(__dirname, 'src') }
import { example } from '@/js/example'

// Test import of an asset
// MIGRATED: @/ alias path — same alias applies; Vite handles SVG as a URL import natively
import webpackLogo from '@/images/webpack-logo.svg'

// Test import of styles
// MIGRATED: @/ alias path — Vite handles SCSS natively (requires `sass` devDependency, already present)
import '@/styles/index.scss'

// Appending to the DOM
const logo = document.createElement('img')
logo.src = webpackLogo

const heading = document.createElement('h1')
heading.textContent = example()

// Test a background image url in CSS
const imageBackground = document.createElement('div')
imageBackground.classList.add('image')

// Test a public folder asset
const imagePublic = document.createElement('img')
imagePublic.src = '/assets/example.png'

const app = document.querySelector('#root')
app.append(logo, heading, imageBackground, imagePublic)