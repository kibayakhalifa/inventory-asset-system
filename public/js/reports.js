// public/js/reports.js
document.addEventListener('DOMContentLoaded', function() {
    // DOM Elements
    const reportType = document.getElementById('report-type');
    const dateRange = document.getElementById('date-range');
    const customDateRange = document.getElementById('custom-date-range');
    const generateBtn = document.getElementById('generate-report');
    const exportPdf = document.getElementById('export-pdf');
    const exportExcel = document.getElementById('export-excel');
    const reportDisplay = document.querySelector('.report-display');
    const loadingIndicator = document.querySelector('.report-loading');
    const errorDisplay = document.querySelector('.report-error');
    const errorMessage = document.querySelector('.error-message');

    // Toggle custom date range visibility
    dateRange.addEventListener('change', function() {
        if (this.value === 'custom') {
            customDateRange.style.display = 'block';
        } else {
            customDateRange.style.display = 'none';
        }
    });

    // Generate report handler
    generateBtn.addEventListener('click', function() {
        // Show loading state
        loadingIndicator.style.display = 'flex';
        errorDisplay.style.display = 'none';
        
        // Get selected values
        const selectedReport = reportType.value;
        const selectedRange = dateRange.value;
        let startDate, endDate;
        
        if (selectedRange === 'custom') {
            startDate = document.getElementById('start-date').value;
            endDate = document.getElementById('end-date').value;
            
            if (!startDate || !endDate) {
                showError('Please select both start and end dates');
                return;
            }
        } else {
            // Calculate dates based on selection
            const today = new Date();
            switch(selectedRange) {
                case 'today':
                    startDate = today.toISOString().split('T')[0];
                    endDate = startDate;
                    break;
                case 'week':
                    const firstDay = new Date(today.setDate(today.getDate() - today.getDay()));
                    startDate = firstDay.toISOString().split('T')[0];
                    endDate = new Date().toISOString().split('T')[0];
                    break;
                case 'month':
                    startDate = new Date(today.getFullYear(), today.getMonth(), 1).toISOString().split('T')[0];
                    endDate = new Date(today.getFullYear(), today.getMonth() + 1, 0).toISOString().split('T')[0];
                    break;
            }
        }
        
        // Simulate API call (replace with actual fetch to your backend)
        setTimeout(() => {
            fetchReportData(selectedReport, startDate, endDate);
        }, 1000);
    });
    
    function fetchReportData(reportType, startDate, endDate) {
        // In a real app, you would make an AJAX call to your Laravel backend
        // Example:
        // fetch(`/api/reports?type=${reportType}&start=${startDate}&end=${endDate}`)
        //     .then(response => response.json())
        //     .then(data => displayReport(data))
        //     .catch(error => showError(error.message));
        
        // For demo purposes, we'll use mock data
        const mockData = generateMockData(reportType, startDate, endDate);
        displayReport(mockData);
    }
    
    function generateMockData(reportType, startDate, endDate) {
        // This generates sample data - replace with your actual data structure
        const reportData = {
            type: reportType,
            dateRange: { start: startDate, end: endDate },
            title: `${reportType.replace('-', ' ')} Report`.replace(/\b\w/g, l => l.toUpperCase()),
            generatedAt: new Date().toISOString()
        };
        
        switch(reportType) {
            case 'sales':
                reportData.summary = {
                    totalSales: 12540.75,
                    totalOrders: 84,
                    averageOrder: 149.29
                };
                reportData.chartData = {
                    labels: ['Week 1', 'Week 2', 'Week 3', 'Week 4'],
                    datasets: [{
                        label: 'Sales',
                        data: [2850, 3100, 2950, 3640.75],
                        backgroundColor: '#4e73df'
                    }]
                };
                reportData.tableData = [
                    { date: '2023-05-01', orderId: 'ORD-1001', amount: 150.00, status: 'Completed' },
                    { date: '2023-05-02', orderId: 'ORD-1002', amount: 225.50, status: 'Completed' },
                    // Add more rows as needed
                ];
                break;
                
            case 'inventory':
                // Add inventory mock data
                break;
                
            case 'user-activity':
                // Add user activity mock data
                break;
        }
        
        return reportData;
    }
    
    function displayReport(data) {
        loadingIndicator.style.display = 'none';
        
        // Enable export buttons
        exportPdf.disabled = false;
        exportExcel.disabled = false;
        
        // Create report HTML based on data
        let reportHTML = `
            <div class="report-header">
                <h2>${data.title}</h2>
                <p>Date Range: ${data.dateRange.start} to ${data.dateRange.end}</p>
                <p>Generated on: ${new Date(data.generatedAt).toLocaleString()}</p>
            </div>
        `;
        
        if (data.summary) {
            reportHTML += `
                <div class="report-summary">
                    <h3>Summary</h3>
                    <div class="summary-cards">
                        ${Object.entries(data.summary).map(([key, value]) => `
                            <div class="summary-card">
                                <h4>${key.replace(/([A-Z])/g, ' $1').replace(/^./, str => str.toUpperCase())}</h4>
                                <p>${typeof value === 'number' && key.includes('Sales') ? '$' + value.toFixed(2) : value}</p>
                            </div>
                        `).join('')}
                    </div>
                </div>
            `;
        }
        
        if (data.chartData) {
            reportHTML += `
                <div class="chart-container">
                    <h3>Trends</h3>
                    <canvas id="reportChart"></canvas>
                </div>
            `;
        }
        
        if (data.tableData) {
            reportHTML += `
                <div class="report-table-container">
                    <h3>Detailed Data</h3>
                    <table class="report-table">
                        <thead>
                            <tr>
                                ${Object.keys(data.tableData[0]).map(key => `
                                    <th>${key.replace(/([A-Z])/g, ' $1').replace(/^./, str => str.toUpperCase())}</th>
                                `).join('')}
                            </tr>
                        </thead>
                        <tbody>
                            ${data.tableData.map(row => `
                                <tr>
                                    ${Object.values(row).map(val => `<td>${val}</td>`).join('')}
                                </tr>
                            `).join('')}
                        </tbody>
                    </table>
                </div>
            `;
        }
        
        reportDisplay.innerHTML = reportHTML;
        
        // Initialize chart if chart data exists
        if (data.chartData) {
            initializeChart(data.chartData);
        }
    }
    
    function initializeChart(chartData) {
        // This would use Chart.js in a real implementation
        console.log('Initializing chart with:', chartData);
        // In a real app, you would:
        // const ctx = document.getElementById('reportChart').getContext('2d');
        // new Chart(ctx, { type: 'bar', data: chartData });
        
        // For demo, we'll just show a placeholder
        const canvas = document.getElementById('reportChart');
        if (canvas) {
            canvas.style.backgroundColor = '#f8f9fa';
            canvas.style.display = 'flex';
            canvas.style.alignItems = 'center';
            canvas.style.justifyContent = 'center';
            canvas.innerHTML = '<p>Chart would display here with real data</p>';
        }
    }
    
    function showError(message) {
        loadingIndicator.style.display = 'none';
        errorDisplay.style.display = 'flex';
        errorMessage.textContent = message;
    }
    
    // Export handlers
    exportPdf.addEventListener('click', function() {
        alert('PDF export would be generated here');
    });
    
    exportExcel.addEventListener('click', function() {
        alert('Excel export would be generated here');
    });
});