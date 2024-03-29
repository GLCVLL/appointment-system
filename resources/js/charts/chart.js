import Chart from 'chart.js/auto';

//*** FUNCTIONS ***//
const initChart = (type, elem, labels, data, showAxis = true) => {
    return new Chart(elem, {
        type,
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
                    grid: {
                        display: true,
                        color: '#212a3a',
                    },
                },
                y: {
                    display: showAxis,
                    beginAtZero: true,
                    grid: {
                        display: true,
                        color: '#212a3a',
                    },
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

initChart('line', clientsChart, clientsLabels, clientsData, false);


// Appointments Chart
const appointmentsChart = document.getElementById('chart-appointments');
const appointmentsLabels = JSON.parse(appointmentsChart.dataset.labels);
const appointmentsData = JSON.parse(appointmentsChart.dataset.values);

initChart('bar', appointmentsChart, appointmentsLabels, appointmentsData, false);

// Profits Chart
const profitsChart = document.getElementById('chart-profits');
const profitsLabels = JSON.parse(profitsChart.dataset.labels);
const profitsData = JSON.parse(profitsChart.dataset.values);

initChart('line', profitsChart, profitsLabels, profitsData, false);


// Profits Chart Details
const profitsChartBig = document.getElementById('chart-profits-big');
const profitsLabelsBig = JSON.parse(profitsChartBig.dataset.labels);
const profitsDataBig = JSON.parse(profitsChartBig.dataset.values);

initChart('line', profitsChartBig, profitsLabelsBig, profitsDataBig);
