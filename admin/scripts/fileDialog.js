/**
 * Opens a file selection dialog with a specific file type.
 * @param {string} [accept] - Accepted file type(s) (e.g., "image/*", ".png,.jpg").
 * @returns {Promise<File>} - A promise that resolves with the file selected by the user.
 */
export function fileDialog(accept = '') {
    return new Promise((resolve, reject) => {
        // Dynamically create an input of type file
        const input = document.createElement('input');
        input.type = 'file';
        input.accept = accept;
        input.style.display = 'none';

        // Add the input to the DOM (necessary for some browsers)
        document.body.appendChild(input);

        // When the user selects a file
        input.addEventListener('change', (event) => {
            const file = event.target.files[0];
            document.body.removeChild(input); // Remove the input from the DOM after selection
            if (file) {
                resolve(file); // Resolve the promise with the selected file
            } else {
                reject(new Error('No file selected'));
            }
        });

        // Handle potential errors
        input.addEventListener('error', () => {
            document.body.removeChild(input);
            reject(new Error('Error during file selection'));
        });

        // Open the file selection dialog
        input.click();
    });
}

/**
 * Opens a dialog to select only images.
 * @returns {Promise<File>} - A promise that resolves with the selected image file.
 */
export function imageDialog() {
    return fileDialog('image/*');
}
