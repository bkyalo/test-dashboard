/**
 * Site Activity Heatmap
 * Renders a 7x24 heatmap showing site activity by day and hour
 */

class SiteActivityHeatmap {
    constructor(containerId) {
        this.container = document.getElementById(containerId);
        this.data = null;
        this.maxValue = 0;
        
        if (!this.container) return;
        
        this.init();
    }
    
    async init() {
        await this.loadData();
        this.render();
        this.addEventListeners();
    }
    
    async loadData() {
        // In a real implementation, this would fetch data from your API
        // For now, we'll use sample data
        this.data = this.generateSampleData();
        this.maxValue = Math.max(...this.data.flat());
    }
    
    generateSampleData() {
        // Generate sample data (7 days x 24 hours)
        const days = 7;
        const hours = 24;
        const data = [];
        
        // Generate random data with some patterns
        for (let day = 0; day < days; day++) {
            const dayData = [];
            for (let hour = 0; hour < hours; hour++) {
                // Create some patterns:
                // - Less activity at night
                // - More activity during work hours
                // - Less activity on weekends
                let value = 0;
                const isWeekend = day >= 5; // Saturday or Sunday
                const isNight = hour < 6 || hour > 22;
                const isWorkHours = hour >= 9 && hour <= 17;
                
                value = Math.random() * 100;
                
                if (isNight) value *= 0.2;
                if (isWorkHours) value *= 1.5;
                if (isWeekend) value *= 0.7;
                
                // Add some random spikes
                if (Math.random() > 0.95) value *= 3;
                
                dayData.push(Math.round(value));
            }
            data.push(dayData);
        }
        
        return data;
    }
    
    render() {
        if (!this.container) return;
        
        const days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
        let html = '';
        
        // Create time labels (x-axis) from 12 AM to 11 PM
        let timeLabels = '<div class="time-labels">';
        for (let i = 0; i < 24; i++) {
            const hour = i % 12 || 12; // Convert 0 to 12, 13 to 1, etc.
            const ampm = i < 12 ? 'AM' : 'PM';
            // Only show every 3 hours to avoid clutter
            if (i % 3 === 0) {
                timeLabels += `<span style="grid-column: ${i + 1} / span 3">${hour}${ampm}</span>`;
            }
        }
        timeLabels += '</div>';
        
        // Clear and set up container
        this.container.innerHTML = '';
        this.container.insertAdjacentHTML('beforeend', timeLabels);
        
        this.data.forEach((dayData, dayIndex) => {
            dayData.forEach((value, hourIndex) => {
                const opacity = this.maxValue > 0 ? Math.min((value / this.maxValue) * 0.8 + 0.2, 1) : 0.1;
                const color = `rgba(0, 212, 255, ${opacity})`;
                const hour = hourIndex % 12 || 12; // Convert 0 to 12, 13 to 1, etc.
                const nextHour = (hourIndex + 1) % 12 || 12;
                const ampm = hourIndex < 12 ? 'AM' : 'PM';
                const nextAmpm = (hourIndex + 1) % 24 < 12 ? 'AM' : 'PM';
                const timeLabel = `${hour}${ampm} - ${nextHour}${nextAmpm}`;
                
                html += `
                    <div class="heatmap-cell" 
                         style="background-color: ${color}"
                         data-day="${dayIndex}" 
                         data-hour="${hourIndex}"
                         data-value="${value}">
                        <span class="tooltip">
                            ${days[dayIndex]}<br>
                            ${timeLabel}<br>
                            <strong>${value} visits</strong>
                        </span>
                    </div>
                `;
            });
        });
        
        // Create a wrapper for the grid cells
        const gridContainer = document.createElement('div');
        gridContainer.className = 'heatmap-grid-inner';
        gridContainer.style.display = 'grid';
        gridContainer.style.gridTemplateColumns = 'repeat(24, 1fr)';
        gridContainer.style.gridTemplateRows = 'repeat(7, 1fr)';
        gridContainer.style.gap = '4px';
        gridContainer.style.width = '100%';
        gridContainer.style.height = '100%';
        
        // Add cells to the grid
        gridContainer.innerHTML = html;
        
        // Append the grid to the container
        this.container.appendChild(gridContainer);
    }
    
    addEventListeners() {
        // Add any event listeners here
    }
    
    refresh() {
        this.loadData().then(() => {
            this.render();
        });
    }
}

// Initialize heatmap when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    // Small delay to ensure DOM is fully rendered
    setTimeout(() => {
        window.siteActivityHeatmap = new SiteActivityHeatmap('siteVisitsHeatmap');
        
        // Handle window resize
        let resizeTimer;
        window.addEventListener('resize', () => {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(() => {
                if (window.siteActivityHeatmap) {
                    window.siteActivityHeatmap.render();
                }
            }, 250);
        });
    }, 100);
    
    // Expose refresh function to global scope for the refresh button
    window.refreshHeatmap = function() {
        if (window.siteActivityHeatmap) {
            window.siteActivityHeatmap.refresh();
        }
    };
});
