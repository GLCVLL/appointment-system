import Chart from 'chart.js/auto';

//*** FUNCTIONS ***//
const initChart = (elem, labels, data, showAxis = true) => {
    return new Chart(elem, {
        type: 'line',
        data: {
            labels,
            datasets: [{
                data,
                borderWidth: 2,
                pointRadius: 0,
                pointHitRadius: 10,
                tension: 0.3,
            }]
        },
        options: {
            scales: {
                x: {
                    display: showAxis,
                },
                y: {
                    display: showAxis,
                    beginAtZero: true
                }
            },
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });
}


//*** DATA ***//
// New Clients Chart
const clientsChart = document.getElementById('chart-users');
const clientsLabels = JSON.parse(clientsChart.dataset.labels);
const clientsData = JSON.parse(clientsChart.dataset.values);

initChart(clientsChart, clientsLabels, clientsData, false);


// New Profits Chart
const profitsChart = document.getElementById('chart-profits');
const profitsLabels = JSON.parse(profitsChart.dataset.labels);
const profitsData = JSON.parse(profitsChart.dataset.values);

initChart(profitsChart, profitsLabels, profitsData, false);
