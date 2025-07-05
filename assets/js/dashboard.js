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
    this.initializeParticles()
    console.log("🚀 SOMAS Futuristic Analytics Dashboard initialized")
  }

  setupEventListeners() {
    // Refresh button functionality
    const refreshBtns = document.querySelectorAll(".btn")
    refreshBtns.forEach((btn) => {
      btn.addEventListener("click", (e) => {
        if (btn.href && btn.href.includes("refresh=1")) {
          this.showLoading(btn)
        }
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

  initializeParticles() {
    // Create floating particles effect
    const particleContainer = document.createElement("div")
    particleContainer.className = "particles-container"
    particleContainer.style.cssText = `
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      pointer-events: none;
      z-index: -1;
    `
    document.body.appendChild(particleContainer)

    // Create particles
    for (let i = 0; i < 50; i++) {
      this.createParticle(particleContainer)
    }
  }

  createParticle(container) {
    const particle = document.createElement("div")
    particle.style.cssText = `
      position: absolute;
      width: 2px;
      height: 2px;
      background: var(--secondary-color);
      border-radius: 50%;
      opacity: 0.3;
      animation: float ${Math.random() * 10 + 10}s linear infinite;
    `

    particle.style.left = Math.random() * 100 + "%"
    particle.style.top = Math.random() * 100 + "%"

    container.appendChild(particle)

    // Remove and recreate particle after animation
    setTimeout(
      () => {
        if (particle.parentNode) {
          particle.parentNode.removeChild(particle)
          this.createParticle(container)
        }
      },
      (Math.random() * 10 + 10) * 1000,
    )
  }

  toggleSidebarCollapse() {
    const sidebar = document.getElementById("sidebar")
    const icon = document.querySelector(".sidebar-toggle i")

    sidebar.classList.toggle("collapsed")

    if (sidebar.classList.contains("collapsed")) {
      icon.className = "fas fa-chevron-right"
    } else {
      icon.className = "fas fa-chevron-left"
    }
  }

  toggleMobileSidebar() {
    const sidebar = document.getElementById("sidebar")
    const overlay = document.querySelector(".sidebar-overlay")

    sidebar.classList.toggle("open")
    overlay.classList.toggle("active")
  }

  closeMobileSidebar() {
    const sidebar = document.getElementById("sidebar")
    const overlay = document.querySelector(".sidebar-overlay")

    sidebar.classList.remove("open")
    overlay.classList.remove("active")
  }

  updateActiveNavLink(activeLink) {
    // Remove active class from all nav links
    document.querySelectorAll(".nav-link").forEach((link) => {
      link.classList.remove("active")
    })

    // Add active class to clicked link
    activeLink.classList.add("active")
  }

  autoRefresh() {
    // Check if data is stale (older than 10 minutes)
    const lastUpdated = document.querySelector(".header-time")
    if (lastUpdated) {
      this.showStaleDataWarning()
    }
  }

  updateTime() {
    const timeElement = document.getElementById("current-time")
    if (timeElement) {
      const now = new Date()
      const timeString = now.toISOString().slice(0, 19).replace("T", " ")
      timeElement.textContent = timeString
    }
  }

  startRefreshCountdown() {
    let countdown = 300 // 5 minutes in seconds

    const countdownElement = document.createElement("div")
    countdownElement.className = "refresh-countdown"
    countdownElement.style.cssText = `
      position: fixed;
      bottom: 20px;
      right: 20px;
      background: rgba(0, 0, 0, 0.8);
      color: var(--secondary-color);
      padding: 10px 15px;
      border-radius: 25px;
      font-family: 'Orbitron', monospace;
      font-size: 0.8rem;
      border: 1px solid var(--secondary-color);
      backdrop-filter: blur(10px);
      z-index: 1000;
    `

    document.body.appendChild(countdownElement)

    const updateCountdown = () => {
      const minutes = Math.floor(countdown / 60)
      const seconds = countdown % 60
      countdownElement.innerHTML = `
        <i class="fas fa-sync-alt"></i> 
        Next refresh: ${minutes}:${seconds.toString().padStart(2, "0")}
      `

      if (countdown <= 0) {
        countdown = 300 // Reset
        this.showRefreshNotification()
      } else {
        countdown--
      }
    }

    updateCountdown()
    setInterval(updateCountdown, 1000)
  }

  showRefreshNotification() {
    const notification = document.createElement("div")
    notification.className = "refresh-notification"
    notification.style.cssText = `
      position: fixed;
      top: 20px;
      right: 20px;
      background: linear-gradient(135deg, var(--success-color), #00cc7a);
      color: white;
      padding: 15px 20px;
      border-radius: 10px;
      font-weight: 600;
      z-index: 1001;
      transform: translateX(100%);
      transition: transform 0.3s ease;
      box-shadow: 0 10px 30px rgba(0, 255, 136, 0.3);
    `

    notification.innerHTML = `
      <i class="fas fa-check-circle"></i> 
      Data refreshed successfully!
    `

    document.body.appendChild(notification)

    // Animate in
    setTimeout(() => {
      notification.style.transform = "translateX(0)"
    }, 100)

    // Remove after 3 seconds
    setTimeout(() => {
      notification.style.transform = "translateX(100%)"
      setTimeout(() => {
        if (notification.parentNode) {
          notification.parentNode.removeChild(notification)
        }
      }, 300)
    }, 3000)
  }

  showExportNotification() {
    const notification = document.createElement("div")
    notification.style.cssText = `
      position: fixed;
      top: 20px;
      left: 50%;
      transform: translateX(-50%) translateY(-100%);
      background: linear-gradient(135deg, var(--info-color), var(--secondary-color));
      color: white;
      padding: 15px 25px;
      border-radius: 10px;
      font-weight: 600;
      z-index: 1001;
      transition: transform 0.3s ease;
      box-shadow: 0 10px 30px rgba(139, 92, 246, 0.3);
    `

    notification.innerHTML = `
      <i class="fas fa-download"></i> 
      Data exported successfully!
    `

    document.body.appendChild(notification)

    // Animate in
    setTimeout(() => {
      notification.style.transform = "translateX(-50%) translateY(0)"
    }, 100)

    // Remove after 3 seconds
    setTimeout(() => {
      notification.style.transform = "translateX(-50%) translateY(-100%)"
      setTimeout(() => {
        if (notification.parentNode) {
          notification.parentNode.removeChild(notification)
        }
      }, 300)
    }, 3000)
  }
}

// Global functions for PHP template compatibility
function showSection(sectionId) {
  // Hide all sections
  const sections = document.querySelectorAll(".dashboard-section")
  sections.forEach((section) => {
    section.style.display = "none"
  })

  // Show selected section with animation
  const targetSection = document.getElementById(sectionId)
  if (targetSection) {
    targetSection.style.display = "block"
    targetSection.style.opacity = "0"
    targetSection.style.transform = "translateY(20px)"

    setTimeout(() => {
      targetSection.style.transition = "all 0.3s ease"
      targetSection.style.opacity = "1"
      targetSection.style.transform = "translateY(0)"
    }, 50)
  }

  // Update active nav link
  const navLinks = document.querySelectorAll(".nav-link")
  navLinks.forEach((link) => {
    link.classList.remove("active")
  })

  const activeLink = document.querySelector(`[onclick="showSection('${sectionId}')"]`)
  if (activeLink) {
    activeLink.classList.add("active")
  }
}

function toggleSection(sectionId) {
  const section = document.getElementById(sectionId)
  if (section) {
    section.classList.toggle("hidden")

    // Update button text with icons
    const button = event.target.closest("button")
    if (button) {
      if (section.classList.contains("hidden")) {
        button.innerHTML = '<i class="fas fa-eye"></i> Show Details'
      } else {
        button.innerHTML = '<i class="fas fa-eye-slash"></i> Hide Details'
      }
    }
  }
}

function toggleSidebar() {
  const sidebar = document.getElementById("sidebar")
  const overlay = document.querySelector(".sidebar-overlay")

  sidebar.classList.toggle("open")
  overlay.classList.toggle("active")
}

function closeSidebar() {
  const sidebar = document.getElementById("sidebar")
  const overlay = document.querySelector(".sidebar-overlay")

  sidebar.classList.remove("open")
  overlay.classList.remove("active")
}

function toggleSidebarCollapse() {
  const sidebar = document.getElementById("sidebar")
  const icon = document.querySelector(".sidebar-toggle i")

  sidebar.classList.toggle("collapsed")

  if (sidebar.classList.contains("collapsed")) {
    icon.className = "fas fa-chevron-right"
  } else {
    icon.className = "fas fa-chevron-left"
  }
}

function updatePDCCoursesCount() {
  // Update PDC courses count in navigation
  const pdcCountElement = document.getElementById("pdc-courses-count")
  const pdcTotalElement = document.querySelector("#short-courses .stat-value")

  if (pdcCountElement && pdcTotalElement) {
    const count = pdcTotalElement.textContent.trim()
    pdcCountElement.textContent = count
  }
}

// Initialize dashboard when DOM is loaded
document.addEventListener("DOMContentLoaded", () => {
  window.somosAnalytics = new SOMASAnalytics()

  // Show overview section by default
  showSection("overview")
})

// Call this function after dashboard loads
document.addEventListener("DOMContentLoaded", () => {
  setTimeout(updatePDCCoursesCount, 1000)
})

// Add CSS for enhanced animations and effects
const style = document.createElement("style")
style.textContent = `
  /* Enhanced table row highlighting */
  .data-table tbody tr.highlighted {
    background-color: #fff3cd !important;
    border-left: 4px solid #ffc107;
  }
  
  /* Floating particles animation */
  @keyframes float {
    0% {
      transform: translateY(100vh) rotate(0deg);
      opacity: 0;
    }
    10% {
      opacity: 0.3;
    }
    90% {
      opacity: 0.3;
    }
    100% {
      transform: translateY(-100vh) rotate(360deg);
      opacity: 0;
    }
  }
  
  /* Stale data warning animation */
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
  
  /* Card entrance animation */
  @keyframes slideInUp {
    from {
      opacity: 0;
      transform: translateY(50px);
    }
    to {
      opacity: 1;
      transform: translateY(0);
    }
  }
  
  .animate-in {
    animation: slideInUp 0.6s ease-out forwards;
  }
  
  /* Enhanced button hover effects */
  .btn:hover {
    animation: buttonPulse 0.3s ease;
  }
  
  @keyframes buttonPulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.05); }
    100% { transform: scale(1); }
  }
  
  /* Sidebar transition improvements */
  .sidebar {
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
  }
  
  .nav-link {
    position: relative;
    overflow: hidden;
  }
  
  .nav-link::after {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(0, 212, 255, 0.1), transparent);
    transition: left 0.5s ease;
  }
  
  .nav-link:hover::after {
    left: 100%;
  }
`
document.head.appendChild(style)
