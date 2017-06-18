<table class="table">
  <thead>
      <tr>
        <th>S.No.</th>
        <th>Username</th>
        <th>Email</th>
      </tr>
  </thead>
  <tbody>
    {{#each data}}
      <tr>
        <td>{{ this.[0] }}</td>
        <td>{{ this.[1] }}</td>
        <td>{{ this.[2] }}</td>
      </tr>
    {{/each}}
  </tbody>
</table>