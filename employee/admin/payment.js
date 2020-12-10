const getDue = () => {
  const id = pid.value
  const http = new window.XMLHttpRequest()
  http.onreadystatechange = function () {
    if (this.readyState === 4 && this.status === 200) {
      document.getElementById('pname').value = this.responseText
      this.responseText === 'Invalid Patient ID' ? submit.setAttribute('disabled', '') : submit.removeAttribute('disabled')
    }
  }
  http.open('POST', 'name.php')
  http.setRequestHeader('Content-type', 'application/x-www-form-urlencoded')
  http.send('id=' + id)
}

const pid = document.getElementById('pid')
const submit = document.getElementById('submit')
pid.addEventListener('change', getName)
