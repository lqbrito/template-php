
                    </div>
                    <div class="app-wrapper-footer">
                        <div class="app-footer">
                            <div class="app-footer__inner">
                                <div class="app-footer-left">
                                    <strong>Copyright &copy; </strong> 2020 - <script>
                                        document.write(new Date().getFullYear())
                                      </script>, Todos os direitos reservados.
                                </div>
                                <div class="app-footer-right">
                                    <b>Versão</b> 1.0
                                </div>
                            </div>
                        </div>
                    </div>    
                </div>
                <script src="http://maps.google.com/maps/api/js?sensor=true"></script>
        </div>
    </div>
    <script type="text/javascript" src="../themes/architectui/assets/scripts/main.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
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
