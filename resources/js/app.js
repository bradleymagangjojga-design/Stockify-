import './bootstrap';
import 'flowbite';
import 'flowbite-datepicker';
import ApexCharts from 'apexcharts';

window.ApexCharts = ApexCharts;

document.addEventListener('DOMContentLoaded', () => {
    const search = document.getElementById('productSearch');
    if (search) {
        search.addEventListener('input', function () {
            const term = this.value.toLowerCase();
            document.querySelectorAll('#productTable tbody tr').forEach(row => {
                row.style.display = row.textContent.toLowerCase().includes(term) ? '' : 'none';
            });
        });
    }

    setTimeout(() => {
        document.querySelectorAll('.alert-auto-dismiss').forEach(el => el.remove());
    }, 4000);
});