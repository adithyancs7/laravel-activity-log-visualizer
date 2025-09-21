document.addEventListener('DOMContentLoaded', function () {
    const form = document.querySelector('form');
    const inputs = form.querySelectorAll('input, select');

    inputs.forEach(el => {
        el.addEventListener('change', () => { if (el.name !== 'search') form.submit(); });
    });

    document.getElementById('search')?.addEventListener('keypress', e => { if (e.key === 'Enter') form.submit(); });

    const exportBtn = document.querySelector('.export-btn');
    if (exportBtn && !exportBtn.disabled) {
        exportBtn.addEventListener('click', async () => {
            exportBtn.disabled = true; exportBtn.textContent = 'Exporting...';
            try {
                const params = new URLSearchParams(new FormData(form));
                params.append('export', 'csv');
                const res = await fetch(`/activity-log-visualizer/export?${params}`);
                if (!res.ok) throw new Error('Export failed');
                const blob = await res.blob();
                const a = document.createElement('a');
                a.href = window.URL.createObjectURL(blob);
                a.download = `activity-log-${new Date().toISOString().split('T')[0]}.csv`;
                a.click();
            } catch (e) { alert(e.message); }
            finally { exportBtn.disabled = false; exportBtn.textContent = 'Export'; }
        });
    }

    // Modal
    const rows = document.querySelectorAll('.activity-row');
    const modalEl = document.getElementById('activityDetailsModal');
    const modal = new bootstrap.Modal(modalEl);
    const detailsTable = document.getElementById('activity-details-table');
    const propertiesPre = document.getElementById('activity-properties');

    rows.forEach(row => row.addEventListener('click', () => {
    try {
        const activity = JSON.parse(row.dataset.activity);

        // Define order of fields
        const fieldOrder = [
            "id",
            "log_name",
            "description",
            "subject_type",
            "subject",
            "event",
            "causer_type",
            "causer",
            "created_at",
            "properties" // show last
        ];

        let html = '';

        fieldOrder.forEach(key => {
            if (!(key in activity)) return; // Skip if missing
            let val = activity[key];

            let displayValue;
            if (val === null || val === undefined) {
                displayValue = '—';
            } 
            else if (typeof val === 'object') {
                displayValue = `<pre class="mb-0 bg-light p-2 rounded border" style="max-height:150px; overflow:auto;">${JSON.stringify(val, null, 2)}</pre>`;
            } 
            else {
                displayValue = val;
            }

            html += `<tr><th>${key.replace(/_/g, ' ')}</th><td>${displayValue}</td></tr>`;
        });

        detailsTable.innerHTML = html;

        // Pretty-print properties separately
        // propertiesPre.textContent = activity.properties
        //     ? JSON.stringify(activity.properties, null, 2)
        //     : '—';

        modal.show();
    } catch (e) {
        console.error("Failed to load activity details", e);
    }
}));


});
