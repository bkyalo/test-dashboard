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

    // Initialize PDC pagination if showing short-courses section
    if (sectionId === "short-courses") {
      setTimeout(() => {
        initializePDCPagination()
        // Also expand the details section by default
        const pdcDetails = document.getElementById("pdc-details")
        if (pdcDetails && pdcDetails.classList.contains("hidden")) {
          toggleSection("pdc-details")
        }
      }, 200)
    }
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

    // If showing PDC details, initialize pagination
    if (sectionId === "pdc-details" && !section.classList.contains("hidden")) {
      setTimeout(initializePDCPagination, 100)
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

  // Initialize PDC pagination immediately if data exists
  setTimeout(initializePDCPagination, 500)
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
  
  /* Pagination styles */
  .pagination-controls {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin: 1rem 0;
    padding: 1rem;
    background: rgba(0, 0, 0, 0.1);
    border-radius: 8px;
    border: 1px solid rgba(0, 212, 255, 0.2);
  }
  
  .pagination-buttons {
    display: flex;
    gap: 1rem;
    align-items: center;
  }
  
  .pagination-btn {
    background: linear-gradient(135deg, #1a1a2e, #16213e);
    color: #00d4ff;
    border: 1px solid #00d4ff;
    padding: 0.5rem 1rem;
    border-radius: 5px;
    cursor: pointer;
    transition: all 0.3s ease;
    font-size: 0.9rem;
  }
  
  .pagination-btn:hover:not(:disabled) {
    background: linear-gradient(135deg, #00d4ff, #0066cc);
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0, 212, 255, 0.3);
  }
  
  .pagination-btn:disabled {
    opacity: 0.5;
    cursor: not-allowed;
    background: #333;
    color: #666;
    border-color: #666;
  }
  
  .pagination-current {
    color: #00d4ff;
    font-weight: 600;
  }
  
  .pagination-info, .pagination-summary {
    color: #ccc;
    font-size: 0.9rem;
  }
`
document.head.appendChild(style)

// PDC Courses Pagination
let pdcCurrentPage = 1
const pdcCoursesPerPage = 10
let pdcCoursesData = []

function initializePDCPagination() {
  console.log("Initializing PDC pagination...")
  const dataElement = document.getElementById("pdc-courses-data")

  if (dataElement) {
    try {
      const jsonText = dataElement.textContent.trim()
      console.log("PDC data found:", jsonText.substring(0, 100) + "...")

      if (jsonText) {
        pdcCoursesData = JSON.parse(jsonText)
        console.log("Parsed PDC courses:", pdcCoursesData.length, "courses")

        if (pdcCoursesData.length > 0) {
          updatePDCTable()
          updatePDCPaginationControls()
          console.log("PDC table updated successfully")
        } else {
          console.log("No PDC courses data available")
          const tableBody = document.getElementById("pdc-courses-table")
          if (tableBody) {
            tableBody.innerHTML =
              '<tr><td colspan="6" style="text-align: center; padding: 2rem; color: #999;">No PDC courses found</td></tr>'
          }
        }
      }
    } catch (e) {
      console.error("Error parsing PDC courses data:", e)
      const tableBody = document.getElementById("pdc-courses-table")
      if (tableBody) {
        tableBody.innerHTML =
          '<tr><td colspan="6" style="text-align: center; padding: 2rem; color: #f44336;">Error loading PDC courses data</td></tr>'
      }
    }
  } else {
    console.log("PDC courses data element not found")
    const tableBody = document.getElementById("pdc-courses-table")
    if (tableBody) {
      tableBody.innerHTML =
        '<tr><td colspan="6" style="text-align: center; padding: 2rem; color: #999;">PDC courses data not available</td></tr>'
    }
  }
}

function changePDCPage(direction) {
  const totalPages = Math.ceil(pdcCoursesData.length / pdcCoursesPerPage)

  if (direction === 1 && pdcCurrentPage < totalPages) {
    pdcCurrentPage++
  } else if (direction === -1 && pdcCurrentPage > 1) {
    pdcCurrentPage--
  }

  updatePDCTable()
  updatePDCPaginationControls()
}

function updatePDCTable() {
  const tableBody = document.getElementById("pdc-courses-table")
  if (!tableBody || pdcCoursesData.length === 0) {
    console.log("No table body or no data")
    return
  }

  const startIndex = (pdcCurrentPage - 1) * pdcCoursesPerPage
  const endIndex = Math.min(startIndex + pdcCoursesPerPage, pdcCoursesData.length)
  const currentPageData = pdcCoursesData.slice(startIndex, endIndex)

  console.log(`Displaying courses ${startIndex + 1} to ${endIndex} of ${pdcCoursesData.length}`)

  let html = ""
  currentPageData.forEach((course) => {
    const statusBadge = course.visible
      ? '<span class="status-badge online"><i class="fas fa-eye"></i> Visible</span>'
      : '<span class="status-badge offline"><i class="fas fa-eye-slash"></i> Hidden</span>'

    const createdDate = course.created > 0 ? new Date(course.created * 1000).toLocaleDateString() : "Unknown"
    const moodleUrl = window.MOODLE_URL || ""

    html += `
      <tr>
        <td>${escapeHtml(course.name)}</td>
        <td><code>${escapeHtml(course.shortname)}</code></td>
        <td><span class="badge">${course.enrollments.toLocaleString()}</span></td>
        <td>${statusBadge}</td>
        <td>${createdDate}</td>
        <td>
          <a href="${moodleUrl}/course/view.php?id=${course.id}" 
             target="_blank" 
             class="action-btn view-btn" 
             title="View Course">
            <i class="fas fa-external-link-alt"></i> View
          </a>
        </td>
      </tr>
    `
  })

  tableBody.innerHTML = html
  console.log("Table HTML updated")
}

function updatePDCPaginationControls() {
  const totalPages = Math.ceil(pdcCoursesData.length / pdcCoursesPerPage)
  const startIndex = (pdcCurrentPage - 1) * pdcCoursesPerPage + 1
  const endIndex = Math.min(pdcCurrentPage * pdcCoursesPerPage, pdcCoursesData.length)

  // Update pagination info
  const startElement = document.getElementById("pdc-start")
  const endElement = document.getElementById("pdc-end")
  const currentPageElement = document.getElementById("pdc-current-page")
  const totalPagesElement = document.getElementById("pdc-total-pages")

  if (startElement) startElement.textContent = startIndex
  if (endElement) endElement.textContent = endIndex
  if (currentPageElement) currentPageElement.textContent = pdcCurrentPage
  if (totalPagesElement) totalPagesElement.textContent = totalPages

  // Update button states
  const prevButtons = ["pdc-prev", "pdc-prev-bottom"]
  const nextButtons = ["pdc-next", "pdc-next-bottom"]

  prevButtons.forEach((id) => {
    const btn = document.getElementById(id)
    if (btn) {
      btn.disabled = pdcCurrentPage <= 1
      btn.classList.toggle("disabled", pdcCurrentPage <= 1)
    }
  })

  nextButtons.forEach((id) => {
    const btn = document.getElementById(id)
    if (btn) {
      btn.disabled = pdcCurrentPage >= totalPages
      btn.classList.toggle("disabled", pdcCurrentPage >= totalPages)
    }
  })

  console.log(`Pagination updated: Page ${pdcCurrentPage} of ${totalPages}`)
}

function escapeHtml(text) {
  const div = document.createElement("div")
  div.textContent = text
  return div.innerHTML
}
