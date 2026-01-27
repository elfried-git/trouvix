// Petit son bip (440Hz, 100ms)
export function playBip() {
    const ctx = new (window.AudioContext || window.webkitAudioContext)();
    const o = ctx.createOscillator();
    const g = ctx.createGain();
    o.type = 'sine';
    o.frequency.value = 440;
    g.gain.value = 0.15;
    o.connect(g).connect(ctx.destination);
    o.start();
    setTimeout(() => {
        o.stop();
        ctx.close();
    }, 100);
}