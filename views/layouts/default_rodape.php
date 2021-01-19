    </div>
  </main>

  <footer class="footer mt-auto py-3">
    <div class="container">
      <div class="float-right d-none d-sm-block">
        <b>Versão</b> 1.0
      </div>
      <strong>Copyright &copy; </strong>2020 - <script>
        document.write(new Date().getFullYear())
      </script>, Todos os direitos reservados.
    </div>
  </footer>
  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
  <script>window.jQuery || document.write('<script src="assets/js/vendor/jquery.slim.min.js"><\/script>')</script><script src="../themes/default/dist/js/bootstrap.bundle.min.js"></script>
  <script type="text/javascript">
    var alertList = document.querySelectorAll('.alert')
    alertList.forEach(function (alert) {
      new bootstrap.Alert(alert)
    })
  </script>

  <script>
    $(function () {
      $("#textobusca").on("input", function(){
        var textobusca = $(this).val();
          $("#pesquisar").attr("disabled", textobusca.length > 0 && textobusca.length < 3);
      });
      
    });
  </script>
</body>

</html>