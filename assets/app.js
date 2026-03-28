import './stimulus_bootstrap.js';
/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */
import './styles/app.css';

console.log('This log comes from assets/app.js - welcome to AssetMapper! 🎉');

const textarea = document.querySelector('.show_code');
const codeWindow = document.querySelector('.code_window');
if (textarea && codeWindow) {
    textarea.addEventListener('input', () => {
        codeWindow.innerHTML = textarea.value;
    });
}

const btn = document.querySelector("#modify_btn")
btn.addEventListener('click', async () => {
    const prompt = document.querySelector('#aiprompt').value.trim();
    const htmlCode = textarea.value;

    if (!prompt) return;
    btn.disabled = true;

    try {
        const response = await fetch('/api/component/modify', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({prompt, htmlCode})
        });

        const data = await response.json();
        textarea.value = data.code;
        textarea.dispatchEvent(new Event('input')); // déclenche la preview
    } catch (e) {
        console.error(e)
    } finally {
        btn.disabled = false;
    }
});
