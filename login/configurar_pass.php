<div class="linha">
  <form method="POST" id="falterarpass" onsubmit="return checkForm(this);">
    <div class="passw">
      <input type="hidden" name="iduser" value="<?php print($userId); ?>">
      <input type="password" name="pass1" id="pass1" placeholder="Senha" required="required">
      <input type="password" name="pass2" id="pass2" placeholder="Confirma Senha" required="required">
    </div>
    <input type="submit" class="input" name="alterarpass" value="Alterar Senha">
  </form>
</div>

<?php
  if (isset($_POST["alterarpass"])) {
    $id = $_POST["iduser"];
    $pass1 = $_POST["pass1"];
    $pass2 = $_POST["pass2"];
    
    $sql="update utilizadores set pass='" . md5($pass1) . "' where id={$id}";
    $result=$connect->query($sql);
  } 
?>

<script>
  function checkForm() {
    let pass1 = document.getElementById("pass1").value;
    let pass2 = document.getElementById("pass2").value;

    if (pass1 != pass2) {
        alert("As senhas devem ser iguais.");
        return false;
    } else {
        document.getElementById("falterarpass").submit();
        return true;
    }
  }
</script>
