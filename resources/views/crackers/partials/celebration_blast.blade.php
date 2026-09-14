@if(session('show_login_celebration'))
<!-- Celebration Cracker Blast Canvas & Festive Banner -->
<div id="loginCelebrationOverlay" style="position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; pointer-events: none; z-index: 999999; display: flex; align-items: center; justify-content: center;">
    <canvas id="celebrationCanvas" style="position: absolute; top:0; left:0; width:100%; height:100%;"></canvas>

    <div id="celebrationBanner" style="position: relative; z-index: 10; background: linear-gradient(135deg, rgba(124, 45, 18, 0.96), rgba(194, 65, 12, 0.96)); border: 2px solid #fbbf24; box-shadow: 0 25px 60px rgba(251, 146, 60, 0.55), 0 0 35px rgba(254, 240, 138, 0.65); border-radius: 24px; padding: 1.5rem 2.5rem; text-align: center; color: #ffffff; backdrop-filter: blur(16px); transform: translateY(-30px) scale(0.9); opacity: 0; transition: all 0.5s cubic-bezier(0.34, 1.56, 0.64, 1); pointer-events: auto; max-width: 480px; margin: 1rem;">
        <div style="font-size: 2.4rem; margin-bottom: 0.2rem;">🎆 💥 🎇</div>
        <div style="font-family: 'Outfit', sans-serif; font-size: 0.82rem; font-weight: 800; text-transform: uppercase; letter-spacing: 2px; color: #fef08a; margin-bottom: 0.25rem;">
            Diwali Cracker Blast Celebration
        </div>
        <h3 style="font-family: 'Outfit', sans-serif; font-weight: 800; font-size: 1.6rem; color: #ffffff; margin-bottom: 0.35rem; text-shadow: 0 0 15px rgba(254,240,138,0.5);">
            Welcome Back, {{ session('celebration_user') ?: (auth()->check() ? auth()->user()->name : 'Valued Customer') }}!
        </h3>
        <p style="font-size: 0.9rem; color: #ffedd5; margin-bottom: 0; line-height: 1.5;">
            Login successful! Enjoy 100% genuine green crackers & Sivakasi factory discounts!
        </p>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const overlay = document.getElementById('loginCelebrationOverlay');
    const banner = document.getElementById('celebrationBanner');
    const canvas = document.getElementById('celebrationCanvas');
    if (!overlay || !canvas || !banner) return;

    // Show banner animation
    setTimeout(() => {
        banner.style.transform = 'translateY(0) scale(1)';
        banner.style.opacity = '1';
    }, 100);

    const ctx = canvas.getContext('2d');
    canvas.width = window.innerWidth;
    canvas.height = window.innerHeight;

    window.addEventListener('resize', () => {
        canvas.width = window.innerWidth;
        canvas.height = window.innerHeight;
    });

    const rockets = [];
    const particles = [];
    const colors = ['#ff0055', '#ffb703', '#00f5d4', '#7b2cbf', '#ff5400', '#22c55e', '#3b82f6', '#f43f5e', '#facc15', '#ec4899'];

    class Rocket {
        constructor(startX, startY, targetX, targetY) {
            this.x = startX;
            this.y = startY;
            this.targetX = targetX;
            this.targetY = targetY;
            this.speed = 10 + Math.random() * 5;
            const angle = Math.atan2(targetY - startY, targetX - startX);
            this.vx = Math.cos(angle) * this.speed;
            this.vy = Math.sin(angle) * this.speed;
            this.color = colors[Math.floor(Math.random() * colors.length)];
            this.trail = [];
            this.exploded = false;
        }
        update() {
            this.trail.push({ x: this.x, y: this.y });
            if (this.trail.length > 8) this.trail.shift();
            this.x += this.vx;
            this.y += this.vy;
            const dist = Math.hypot(this.targetX - this.x, this.targetY - this.y);
            if (dist < 18 || this.vy >= 0 || this.y <= this.targetY) {
                this.exploded = true;
                createExplosion(this.x, this.y, this.color);
            }
        }
        draw() {
            for (let i = 0; i < this.trail.length; i++) {
                const pt = this.trail[i];
                ctx.beginPath();
                ctx.arc(pt.x, pt.y, (i + 1) * 0.7, 0, Math.PI * 2);
                ctx.fillStyle = `rgba(255, 220, 100, ${i / this.trail.length})`;
                ctx.fill();
            }
        }
    }

    class Particle {
        constructor(x, y, color) {
            this.x = x;
            this.y = y;
            this.color = color;
            const angle = Math.random() * Math.PI * 2;
            const speed = 3 + Math.random() * 9;
            this.vx = Math.cos(angle) * speed;
            this.vy = Math.sin(angle) * speed;
            this.gravity = 0.12;
            this.friction = 0.95;
            this.alpha = 1;
            this.decay = 0.015 + Math.random() * 0.02;
            this.size = 2.5 + Math.random() * 3;
        }
        update() {
            this.vx *= this.friction;
            this.vy *= this.friction;
            this.vy += this.gravity;
            this.x += this.vx;
            this.y += this.vy;
            this.alpha -= this.decay;
        }
        draw() {
            ctx.save();
            ctx.globalAlpha = Math.max(0, this.alpha);
            ctx.beginPath();
            ctx.arc(this.x, this.y, this.size, 0, Math.PI * 2);
            ctx.fillStyle = this.color;
            ctx.shadowBlur = 14;
            ctx.shadowColor = this.color;
            ctx.fill();
            ctx.restore();
        }
    }

    function createExplosion(x, y, color) {
        const count = 55 + Math.floor(Math.random() * 35);
        for (let i = 0; i < count; i++) {
            particles.push(new Particle(x, y, color));
        }
    }

    function launchRocket() {
        const startX = canvas.width * 0.15 + Math.random() * canvas.width * 0.7;
        const targetX = startX + (Math.random() * 120 - 60);
        const targetY = canvas.height * 0.1 + Math.random() * canvas.height * 0.4;
        rockets.push(new Rocket(startX, canvas.height, targetX, targetY));
    }

    let animId;
    function animate() {
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        for (let i = rockets.length - 1; i >= 0; i--) {
            rockets[i].update();
            rockets[i].draw();
            if (rockets[i].exploded) rockets.splice(i, 1);
        }
        for (let i = particles.length - 1; i >= 0; i--) {
            particles[i].update();
            particles[i].draw();
            if (particles[i].alpha <= 0) particles.splice(i, 1);
        }
        animId = requestAnimationFrame(animate);
    }
    animate();

    // Fire continuous sky shot salvos for 3.5 seconds
    let interval = setInterval(() => {
        launchRocket();
    }, 220);

    // Initial burst
    launchRocket();
    setTimeout(launchRocket, 100);
    setTimeout(launchRocket, 200);

    // Fade out and cleanup after 3.8 seconds
    setTimeout(() => {
        clearInterval(interval);
        banner.style.transform = 'translateY(-30px) scale(0.9)';
        banner.style.opacity = '0';
        overlay.style.transition = 'opacity 0.6s ease';
        overlay.style.opacity = '0';
        setTimeout(() => {
            cancelAnimationFrame(animId);
            overlay.remove();
        }, 600);
    }, 3800);
});
</script>
@endif
