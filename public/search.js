const getResults = () => {
  const oldRow = document.getElementById('name-row')
  if (oldRow) oldRow.remove()
  const date = rdate.value
  const http = new window.XMLHttpRequest()
  http.onreadystatechange = function () {
    if (this.readyState === 4 && this.status === 200) {
      const rosterData = JSON.parse(this.responseText)
      const row = document.createElement('tr')
      row.setAttribute('id', 'name-row')
      for (const name of Object.values(rosterData)) {
        const td = document.createElement('td')
        td.textContent = name
        row.appendChild(td)
      }
      table.appendChild(row)
    }
  }
  http.open('POST', 'search.php')
  http.setRequestHeader('Content-type', 'application/x-www-form-urlencoded')
  http.send('date=' + date)
}

const rdate = document.getElementById('roster-date')
const search = document.getElementById('roster-date-search')
const table = document.getElementById('roster-table')
search.addEventListener('click', getResults)
