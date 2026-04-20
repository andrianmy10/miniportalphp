</div> 
            <footer class="footer mt-auto py-3 border-top">
                <div class="d-flex justify-content-center px-4">
                    <span class="text-center d-block small fw-medium" style="color: #64748b;">
                        Copyright AMY <?= date('Y'); ?> - made with <i class="fa-solid fa-heart" style="color: #ef4444;"></i>
                    </span>
                </div>
            </footer>
        </div> 
    </div> 
</div> 

<script src="<?= base_url('assets/plugins/adminarea/template/vendors/js/vendor.bundle.base.js') ?>"></script>
<script src="<?= base_url('assets/plugins/adminarea/template/js/off-canvas.js') ?>"></script>
<script src="<?= base_url('assets/plugins/adminarea/template/js/hoverable-collapse.js') ?>"></script>
<script src="<?= base_url('assets/plugins/adminarea/template/js/template.js') ?>"></script>

<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/air-datepicker@3.3.5/air-datepicker.min.js"></script>

<script>
    function updateClock() {
        const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        
        const now = new Date();
        const h = String(now.getHours()).padStart(2, '0');
        const m = String(now.getMinutes()).padStart(2, '0');
        const s = String(now.getSeconds()).padStart(2, '0');
        
        const dayName = days[now.getDay()];
        const date = String(now.getDate()).padStart(2, '0');
        const monthName = months[now.getMonth()];
        const year = now.getFullYear();
        
        const timeString = `${h}.${m}.${s} | ${dayName}, ${date} ${monthName} ${year}`;
        
        const clockEls = document.querySelectorAll('.realtime-clock');
        clockEls.forEach(el => el.innerText = timeString);
    }
    setInterval(updateClock, 1000);
    updateClock(); 

    const themeToggleBtns = document.querySelectorAll('.theme-toggle-btn');
    const themeIcons = document.querySelectorAll('.theme-icon');
    const body = document.body;
    
    const avatars = document.querySelectorAll('.profile-avatar');
    const userName = "<?= urlencode($this->session->userdata('nama_asli')); ?>";

    function applyThemeChanges(isDark) {
        themeIcons.forEach(icon => {
            if (isDark) {
                icon.classList.replace('fa-moon', 'fa-sun'); 
                icon.classList.replace('text-primary', 'text-warning'); 
            } else {
                icon.classList.replace('fa-sun', 'fa-moon'); 
                icon.classList.replace('text-warning', 'text-primary'); 
            }
        });

        avatars.forEach(img => {
            if (isDark) {
                img.src = `https://ui-avatars.com/api/?name=${userName}&background=ffffff&color=1e3a8a&bold=true`;
            } else {
                img.src = `https://ui-avatars.com/api/?name=${userName}&background=1e3a8a&color=fff&bold=true`;
            }
        });
    }

    applyThemeChanges(body.classList.contains('dark-mode'));

    themeToggleBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            body.classList.toggle('dark-mode');
            const isDark = body.classList.contains('dark-mode');
            localStorage.setItem('theme', isDark ? 'dark' : 'light');
            applyThemeChanges(isDark); 
        });
    });
</script>
</body>
</html>