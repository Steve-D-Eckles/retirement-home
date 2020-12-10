const getDue = () => {
  const id = pid.value
  const http = new window.XMLHttpRequest()
  http.onreadystatechange = function () {
    if (this.readyState === 4 && this.status === 200) {
      document.getElementById('due').value = this.responseText
    }
  }
  http.open('POST', 'due.php')
  http.setRequestHeader('Content-type', 'application/x-www-form-urlencoded')
  http.send('id=' + id)
}

const resetFields = () => {
  document.getElementById('due').value = ''
  document.getElementById('amount').value = ''
  pid.value = ''
}

const makePayment = () => {
  const id = pid.value
  const amount = document.getElementById('amount').value
  const http = new window.XMLHttpRequest()
  http.onreadystatechange = function () {
    if (this.readyState === 4 && this.status === 200) {
      document.getElementById('due').value = this.responseText
      document.getElementById('amount').value = ''
    }
  }
  http.open('POST', 'payment-submit.php')
  http.setRequestHeader('Content-type', 'application/x-www-form-urlencoded')
  http.send(`id=${id}&amount=${amount}`)
}

const updateDue = () => {
  const http = new window.XMLHttpRequest()
  http.onreadystatechange = function () {
    if (this.readyState === 4 && this.status === 200) {
      const res = JSON.parse(this.responseText)
      for (const pair of res) {
        console.log(pair.patient_id)
        console.log(pair.due)
        if (String(pair.patient_id) === pid.value) {
          document.getElementById('due').value = pair.due
        }
      }
    }
  }
  http.open('GET', 'update.php')
  http.send()
}

const pid = document.getElementById('pid')
const submit = document.getElementById('submit')
const reset = document.getElementById('reset')
const update = document.getElementById('update')
pid.addEventListener('change', getDue)
reset.addEventListener('click', resetFields)
submit.addEventListener('click', makePayment)
update.addEventListener('click', updateDue)
