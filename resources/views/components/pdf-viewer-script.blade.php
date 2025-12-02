<script>
    document.addEventListener('livewire:init', () => {
        Livewire.on('openPdfInNewTab', (event) => {
            const base64 = event.base64;
            const filename = event.filename;

            // Convert base64 to blob
            const byteCharacters = atob(base64);
            const byteNumbers = new Array(byteCharacters.length);
            for (let i = 0; i < byteCharacters.length; i++) {
                byteNumbers[i] = byteCharacters.charCodeAt(i);
            }
            const byteArray = new Uint8Array(byteNumbers);
            const blob = new Blob([byteArray], { type: 'application/pdf' });

            // Create URL and open in new tab
            const url = URL.createObjectURL(blob);
            const newTab = window.open(url, '_blank');

            // Cleanup URL after a short delay
            setTimeout(() => URL.revokeObjectURL(url), 100);
        });
    });
</script>
