import Chart from 'chart.js/auto';

//*** FUNCTIONS ***//
const initChart = (elem, title, labels, data) => {
    return new Chart(elem, {
        type: 'line',
        data: {
            labels,
            datasets: [{
                label: title,
                data,
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
}


//*** DATA ***//
// New Clients Chart
const clientsChart = document.getElementById('chart-users');
const clientsLabels = JSON.parse(clientsChart.dataset.labels);
const clientsData = JSON.parse(clientsChart.dataset.values);

initChart(clientsChart, '# of Clients', clientsLabels, clientsData);


// New Profits Chart
const profitsChart = document.getElementById('chart-profits');
const profitsLabels = JSON.parse(profitsChart.dataset.labels);
const profitsData = JSON.parse(profitsChart.dataset.values);

initChart(profitsChart, '# Profits per Day', profitsLabels, profitsData);
