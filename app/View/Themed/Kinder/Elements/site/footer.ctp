<footer>
    <!-- Footer Area Start -->
    <section class="footer-Content">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 col-md-6 col-sm-6 col-xs-6 col-mb-12">
                    <h3><?php echo Configure::read('Site.title'); ?></h3>
                    <div class="textwidget"><?php echo $siteConfig['Site']['footer'] ?></div>
                    <ul class="footer-social">
                        <li><a class="facebook" href="#"><i class="lni-facebook-filled"></i></a></li>
                        <li><a class="twitter" href="#"><i class="lni-twitter-filled"></i></a></li>
                        <li><a class="linkedin" href="#"><i class="lni-linkedin-fill"></i></a></li>
                        <li><a class="google-plus" href="#"><i class="lni-google-plus"></i></a></li>
                    </ul>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6 col-xs-6 col-mb-12">
                    <div class="widget">
                        <h3 class="block-title">Links</h3>
                        <?php
                        echo $this->requestAction(array(
                            'admin' => false,
                            'controller' => 'menus',
                            'action' => 'menuRodape'
                        ), array('return'));
                        ?>                        
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6 col-xs-6 col-mb-12">
                    <div class="widget">
                        <h3 class="block-title">Contato</h3>
                        <ul class="contact-footer">
                            <li>
                                <strong>Endereço :</strong> <span><?php echo $siteConfig['Site']['endereco'] ?> – <?php echo $siteConfig['Site']['bairro'] ?> - <?php echo $siteConfig['Site']['cidade'] ?> – <?php echo $siteConfig['Site']['uf'] ?>, <?php echo $siteConfig['Site']['cep'] ?></span>
                            </li>
                            <li>
                                <strong>Telefone :</strong> <span><?php echo $siteConfig['Site']['telefone'] ?></span>
                            </li>
                            <li>
                                <strong>E-mail :</strong> <span><a href="#"><?php echo $siteConfig['Site']['email'] ?></a></span>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6 col-xs-6 col-mb-12">
                    <div class="widget">
                        <h3 class="block-title">Instagram</h3>
                        <ul class="instagram-footer">
                            <li><a href="#"><img src="/img/instagram/insta1.jpg" alt=""></a></li>
                            <li><a href="#"><img src="/img/instagram/insta2.jpg" alt=""></a></li>
                            <li><a href="#"><img src="/img/instagram/insta3.jpg" alt=""></a></li>
                            <li><a href="#"><img src="/img/instagram/insta4.jpg" alt=""></a></li>
                            <li><a href="#"><img src="/img/instagram/insta5.jpg" alt=""></a></li>
                            <li><a href="#"><img src="/img/instagram/insta6.jpg" alt=""></a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Footer area End -->

    <!-- Copyright Start  -->
    <div id="copyright">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="site-info float-left">
                        <p>Desenvolvido por <a href="https://alvworks.com.br">AlvWorks</a></p>
                    </div>
                    <div class="float-right">
                        Todos os direitos reservados a <?php echo Configure::read('Site.title'); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Copyright End -->

</footer>