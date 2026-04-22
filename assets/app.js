/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.css';

document.addEventListener("DOMContentLoaded", function () {
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
});
