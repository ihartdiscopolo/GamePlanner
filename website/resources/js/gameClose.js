document.addEventListener('DOMContentLoaded', function () {
    const source = new EventSource('/game/status/stream');
    source.onmessage = (e) => {
        if (e.data === 'closed') {
            source.close();
            window.location.href = '/games';
        }
    };
});