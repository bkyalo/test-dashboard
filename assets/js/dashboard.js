/**
 * SOMAS Analytics Dashboard JavaScript
 */

// Dashboard functionality
class SOMASAnalytics {
  constructor() {
    this.init()
  }

  init() {
    this.setupEventListeners()
    this.setupAutoRefresh()
    this.setupCollapsibleSections()
    this.setupTooltips()
    console.log("SOMAS Analytics Dashboard initialized")
  }

  setupEventListeners() {
    // Refresh button functionality
    const refreshBtns = document.querySelectorAll(".refresh-btn")
    refreshBtns.forEach((btn) => {
      btn.addEventListener("click", (e) => {
        this.showLoading(btn)
      })
    })

    // Card hover effects
    const cards = document.querySelectorAll(".card")
    cards.forEach((card) => {
      card.addEventListener("mouseenter", () => {
        this.animateCard(card)
      })
    })

    // Table row highlighting
    const tableRows = document.querySelectorAll(".data-table tbody tr")
    tableRows.forEach((row) => {
      row.addEventListener("click", () => {
        this.highlightRow(row)
      })
    })
  }

  setupAutoRefresh() {
    // Auto-refresh every 5 minutes
    const autoRefreshInterval = 5 * 60 * 1000 // 5 minutes

    setInterval(() => {
      if (document.visibilityState === "visible") {
        this.autoRefresh()
      }
    }, autoRefreshInterval)

    // Update last updated time every minute
    setInterval(() => {
      this.updateLastUpdatedTime()
    }, 60000)
  }

  setupCollapsibleSections() {
    // Make sections collapsible
    const sectionHeaders = document.querySelectorAll(".section-header")
    sectionHeaders.forEach((header) => {
      header.style.cursor = "pointer"
      header.addEventListener("click", (e) => {
        if (e.target.tagName !== "BUTTON") {
          this.toggleSection(header.parentElement)
        }
      })
    })
  }

  setupTooltips() {
    // Add tooltips to metric cards
    const metrics = document.querySelectorAll(".metric")
    metrics.forEach((metric) => {
      const card = metric.closest(".card")
      const title = card.querySelector("h3").textContent
      metric.title = `${title}: ${metric.textContent}`
    })
  }

  toggleSection(sectionElement) {
    const content = sectionElement.querySelector(".collapsible-content, .stats-grid, .data-section")
    if (content) {
      content.classList.toggle("hidden")

      // Update button text
      const toggleBtn = sectionElement.querySelector("button")
      if (toggleBtn) {
        toggleBtn.textContent = content.classList.contains("hidden") ? "Show Details" : "Hide Details"
      }
    }
  }

  animateCard(card) {
    card.style.transform = "translateY(-2px) scale(1.02)"
    setTimeout(() => {
      card.style.transform = ""
    }, 200)
  }

  highlightRow(row) {
    // Remove previous highlights
    document.querySelectorAll(".data-table tbody tr.highlighted").forEach((r) => {
      r.classList.remove("highlighted")
    })

    // Add highlight to clicked row
    row.classList.add("highlighted")

    // Remove highlight after 3 seconds
    setTimeout(() => {
      row.classList.remove("highlighted")
    }, 3000)
  }

  showLoading(button) {
    const originalText = button.textContent
    button.innerHTML = '<span class="loading"></span> Refreshing...'
    button.disabled = true

    // Re-enable after page reload (this is just visual feedback)
    setTimeout(() => {
      button.textContent = originalText
      button.disabled = false
    }, 2000)
  }

  autoRefresh() {
    // Check if data is stale (older than 10 minutes)
    const lastUpdated = document.querySelector(".last-updated")
    if (lastUpdated) {
      const lastUpdateText = lastUpdated.textContent
      const timeMatch = lastUpdateText.match(/(\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2})/)

      if (timeMatch) {
        const lastUpdateTime = new Date(timeMatch[1])
        const now = new Date()
        const diffMinutes = (now - lastUpdateTime) / (1000 * 60)

        if (diffMinutes > 10) {
          this.showStaleDataWarning()
        }
      }
    }
  }

  updateLastUpdatedTime() {
    const lastUpdated = document.querySelector(".last-updated")
    if (lastUpdated) {
      const now = new Date()
      const timeString = now.toISOString().slice(0, 19).replace("T", " ")
      lastUpdated.textContent = `Last Updated: ${timeString}`
    }
  }

  showStaleDataWarning() {
    // Create warning banner if it doesn't exist
    let warningBanner = document.querySelector(".stale-data-warning")
    if (!warningBanner) {
      warningBanner = document.createElement("div")
      warningBanner.className = "stale-data-warning warning"
      warningBanner.innerHTML = `
                <strong>⚠️ Data may be outdated</strong> - 
                Last update was more than 10 minutes ago. 
                <a href="?refresh=1" style="color: white; text-decoration: underline;">Refresh now</a>
            `

      const main = document.querySelector(".main .container")
      main.insertBefore(warningBanner, main.firstChild)
    }
  }

  // Utility function for external use
  refreshData() {
    window.location.href = window.location.pathname + "?refresh=1"
  }

  // Export data functionality
  exportToCSV(tableId) {
    const table = document.getElementById(tableId)
    if (!table) return

    const csv = []
    const rows = table.querySelectorAll("tr")

    rows.forEach((row) => {
      const cols = row.querySelectorAll("td, th")
      const rowData = Array.from(cols).map((col) => '"' + col.textContent.replace(/"/g, '""') + '"')
      csv.push(rowData.join(","))
    })

    const csvContent = csv.join("\n")
    const blob = new Blob([csvContent], { type: "text/csv" })
    const url = window.URL.createObjectURL(blob)

    const a = document.createElement("a")
    a.href = url
    a.download = `somas-analytics-${new Date().toISOString().slice(0, 10)}.csv`
    document.body.appendChild(a)
    a.click()
    document.body.removeChild(a)
    window.URL.revokeObjectURL(url)
  }
}

// Global function for toggling sections (called from PHP template)
function toggleSection(sectionId) {
  const section = document.getElementById(sectionId)
  if (section) {
    section.classList.toggle("hidden")
  }
}

// Initialize dashboard when DOM is loaded
document.addEventListener("DOMContentLoaded", () => {
  window.somosAnalytics = new SOMASAnalytics()
})

// Add CSS for highlighted rows
const style = document.createElement("style")
style.textContent = `
    .data-table tbody tr.highlighted {
        background-color: #fff3cd !important;
        border-left: 4px solid #ffc107;
    }
    
    .stale-data-warning {
        animation: slideDown 0.3s ease-out;
    }
    
    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
`
document.head.appendChild(style)
