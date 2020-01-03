<template>
  <div id="imc-reference">
    <table class="table table-sm" id="table-imc">
      <tbody>
        <tr>
          <th><h4>IMC</h4></th>
          <td><h2>{{ imc }}</h2></td>
        </tr>
        <tr>
          <th>Insuficiencia ponderal</th>
          <td>< 18.5</td>
        </tr>
        <tr>
          <th>Intervalo normal</th>
          <td>18.5 - 24.9</td>
        </tr>
        <tr>
          <th>Sobrepeso</th>
          <td>&gt;= 25.0</td>
        </tr>
        <tr>
          <th>Pre obesidad</th>
          <td>25.0 - 29.9</td>
        </tr>
        <tr>
          <th>Obesidad</th>
          <td>&gt;= 30.0</td>
        </tr>
        <tr>
          <th>Obesidad Clase I</th>
          <td>30.0 - 34.9</td>
        </tr>
        <tr>
          <th>Obesidad Clase II</th>
          <td>35 - 39.9</td>
        </tr>
        <tr>
          <th>Obesidad Clase III</th>
          <td>&gt;= 40.0</td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script>
  export default {
    name: 'ImcReference',
    props: {
      marine: {}
    },
    computed:{
      imc(){
        let height = (this.marine.height / 100) * (this.marine.height / 100)
        return +String(this.marine.weight / height).substring(0,5)
      }
    },
  }
</script>