const clearTable = () => {
  while (table.children.length >= 1) {
    table.removeChild(table.lastChild)
  }
}

const resetTable = () => {
  clearTable()
  const http = new window.XMLHttpRequest()
  http.onreadystatechange = function () {
    if (this.readyState === 4 && this.status === 200) {
      const inputs = document.getElementsByTagName('input')
      for (const item of inputs) {
        item.value = ''
      }

      const results = JSON.parse(this.responseText)
      for (let i = 0; i < results.length; i++) {
        const row = document.createElement('tr')
        const userId = document.createElement('td')
        userId.textContent = results[i].user_id
        row.appendChild(userId)
        const name = document.createElement('td')
        name.textContent = results[i].name
        row.appendChild(name)
        const age = document.createElement('td')
        age.textContent = results[i].age
        row.appendChild(age)
        const ec = document.createElement('td')
        ec.textContent = results[i].emergency_contact
        row.appendChild(ec)
        const ecr = document.createElement('td')
        ecr.textContent = results[i].ec_relation
        row.appendChild(ecr)
        const date = document.createElement('td')
        date.textContent = results[i].admit_date
        row.appendChild(date)
        table.appendChild(row)
      }
    }
  }
  http.open('GET', 'search-all.php')
  http.send()
}

const getResults = () => {
  clearTable()

  const id = document.getElementsByName('id')[0].value
  const name = document.getElementsByName('name')[0].value
  const age = document.getElementsByName('age')[0].value
  const ec = document.getElementsByName('contact-name')[0].value
  const ecr = document.getElementsByName('contact-rel')[0].value
  const date = document.getElementsByName('date')[0].value

  const http = new window.XMLHttpRequest()
  http.onreadystatechange = function () {
    if (this.readyState === 4 && this.status === 200) {
      const results = JSON.parse(this.responseText)
      for (let i = 0; i < results.length; i++) {
        const row = document.createElement('tr')
        const userId = document.createElement('td')
        userId.textContent = results[i].user_id
        row.appendChild(userId)
        const name = document.createElement('td')
        name.textContent = results[i].name
        row.appendChild(name)
        const age = document.createElement('td')
        age.textContent = results[i].age
        row.appendChild(age)
        const ec = document.createElement('td')
        ec.textContent = results[i].emergency_contact
        row.appendChild(ec)
        const ecr = document.createElement('td')
        ecr.textContent = results[i].ec_relation
        row.appendChild(ecr)
        const date = document.createElement('td')
        date.textContent = results[i].admit_date
        row.appendChild(date)
        table.appendChild(row)
      }
    }
  }
  http.open('POST', 'search.php')
  http.setRequestHeader('Content-type', 'application/x-www-form-urlencoded')
  http.send(`id=${id}&name=${name}&age=${age}&contact_name=${ec}&contact_rel=${ecr}&date=${date}`)
}

const search = document.getElementById('search')
const reset = document.getElementById('reset')
const table = document.getElementById('patients-table')
search.addEventListener('click', getResults)
reset.addEventListener('click', resetTable)
