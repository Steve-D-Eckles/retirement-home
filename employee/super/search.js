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
        const role = document.createElement('td')
        role.textContent = results[i].role_name
        row.appendChild(role)
        const salary = document.createElement('td')
        salary.textContent = results[i].salary
        row.appendChild(salary)
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
  const role = document.getElementsByName('role')[0].value
  const salary = document.getElementsByName('salary')[0].value

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
        const role = document.createElement('td')
        role.textContent = results[i].role_name
        row.appendChild(role)
        const salary = document.createElement('td')
        salary.textContent = results[i].salary
        row.appendChild(salary)
        table.appendChild(row)
      }
    }
  }
  http.open('POST', 'search.php')
  http.setRequestHeader('Content-type', 'application/x-www-form-urlencoded')
  http.send(`id=${id}&name=${name}&role=${role}&salary=${salary}`)
}

const updateSalary = () => {
  clearTable()

  const id = document.getElementsByName('update-id')[0].value
  const salary = document.getElementsByName('update-salary')[0].value
  const http = new window.XMLHttpRequest()
  http.onreadystatechange = function () {
    resetTable()
  }
  http.open('POST', 'salary-update.php')
  http.setRequestHeader('Content-type', 'application/x-www-form-urlencoded')
  http.send(`update-id=${id}&update-salary=${salary}`)
}

const search = document.getElementById('search')
const reset = document.getElementById('reset')
const update = document.getElementById('update')
const table = document.getElementById('employee-table')
search.addEventListener('click', getResults)
reset.addEventListener('click', resetTable)
update.addEventListener('click', updateSalary)
