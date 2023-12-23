<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
</head>
<body>
  Add Invoice
  <hr/>
  <div>
    <?php if (!empty($invoice)) :?>
      Invoice ID: <?= htmlspecialchars($invoice['id'])?><br/>
      Invoice Amount:<?= htmlspecialchars($invoice['amount'])?><br/>
      User:<?= htmlspecialchars($invoice['full_name'])?><br/>
    <?php else : ?>
      <form action="/invoices/create" method="post">
          <label for="email">Email:</label>
          <input name="email" type="text">
          <label for="name">Name:</label>
          <input name="name" type="text">
          <label for="amount">Amount:</label>
          <input name="amount" type="text">
          <input type="submit">
      </form>
    <?php endif?>
  </div>
</body>
</html>
