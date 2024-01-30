import Chart from 'chart.js/auto';
import { formatDate } from '~resources/js/utils/';


// New Clients Chart
const ctx = document.getElementById('chart-users');
const clientLabels = JSON.parse(ctx.dataset.labels);
const clientsData = JSON.parse(ctx.dataset.values);

new Chart(ctx, {
    type: 'line',
    data: {
        labels: clientLabels,
        datasets: [{
            label: '# of Clients',
            data: clientsData,
            borderWidth: 1
        }]
    },
    options: {
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});