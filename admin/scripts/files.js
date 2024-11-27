export async function getFullFilepath(filename, defaultFile) {
    // Vérifiez si le filename est invalide (vide, null ou "N/A")
    if (!filename || filename === "N/A") {
        return defaultFile;
    }

    const fullFilePath = `/api/files/${filename}`;
    try {
        const response = await fetch(fullFilePath);
        if (!response.ok) {
            return defaultFile;
        }
        return fullFilePath;
    } catch {
        return defaultFile;
    }
}
