<section id="contact" class="section">
    <div class="contact-form">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Fale Conosco</h2>
                <p class="section-subtitle">Caso você queira entrar em contato conosco através de e-mail, envie uma mensagem para nós no formulário abaixo!</p>
            </div>
            <div class="row">
                <div class="col-lg-9 col-md-9 col-xs-12">
                    <div class="contact-block">
                        <?php
                        echo $this->Form->create(
                            'Page',
                            array(
                                'url' => 'contact',
                                // 'id' => 'contactForm'
                            )
                        );
                        ?>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <?php
                                    echo $this->Form->input(
                                        'name',
                                        array(
                                            'label' => false,
                                            'placeholder' => 'Seu Nome',
                                            'class' => 'form-control',
                                            'div' => false,
                                            'required' => true,
                                            'required data-error' => 'Informe o seu nome completo'
                                        )
                                    );
                                    ?>
                                    <div class="help-block with-errors"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <?php
                                    echo $this->Form->input(
                                        'email',
                                        array(
                                            'label' => false,
                                            'type' => 'email',
                                            'placeholder' => 'Seu e-mail',
                                            'class' => 'form-control',
                                            'div' => false,
                                            'required' => true,
                                            'required data-error' => 'Informe o seu e-mail'
                                        )
                                    );
                                    ?>
                                    <div class="help-block with-errors"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <?php
                                    echo $this->Form->input(
                                        'phone',
                                        array(
                                            'label' => false,
                                            'placeholder' => 'Telefone',
                                            'class' => 'form-control fone',
                                            'div' => false,
                                            'required' => true,
                                            'required data-error' => 'Telefone'
                                        )
                                    );
                                    ?>
                                    <div class="help-block with-errors"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <?php
                                    echo $this->Form->input(
                                        'subject',
                                        array(
                                            'label' => false,
                                            'placeholder' => 'Assunto',
                                            'class' => 'form-control',
                                            'div' => false,
                                            'required' => true,
                                            'required data-error' => 'Informe o Assunto'
                                        )
                                    );
                                    ?>
                                    <div class="help-block with-errors"></div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <?php
                                    echo $this->Form->input(
                                        'message',
                                        array(
                                            'label' => false,
                                            'type' => 'textarea',
                                            'rows' => 3,
                                            'placeholder' => 'Mensagem',
                                            'class' => 'form-control',
                                            'div' => false,
                                            'required' => true,
                                            'required data-error' => 'Escreva a sua mensagem'
                                        )
                                    );
                                    ?>
                                    <div class="help-block with-errors"></div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <?php echo $this->element('site/recaptcha'); ?>
                                    </div>
                                </div>

                                <div class="submit-button">
                                    <?php
                                    echo $this->Form->submit(
                                        'Enviar Mensagem',
                                        array(
                                            'type'    => 'submit',
                                            'class' => 'btn btn-common btn-effect',
                                            'div'    => false,
                                            'label' => false
                                        )
                                    );
                                    ?>
                                    <div id="msgSubmit" class="h3 hidden"></div>
                                    <div class="clearfix"></div>
                                </div>
                            </div>
                        </div>
                        <?php echo $this->Form->end(); ?>
                    </div>
                </div>
                <div class="col-lg-3 col-md-3 col-xs-12">
                    <div class="contact-deatils">
                        <!-- Content Info -->
                        <div class="contact-info_area">
                            <div class="contact-info">
                                <h5><i class="bi bi-map"></i> Endereço</h5>
                                <p><?php echo $siteConfig['Site']['endereco'] ?> – <?php echo $siteConfig['Site']['bairro'] ?> - <?php echo $siteConfig['Site']['cidade'] ?> – <?php echo $siteConfig['Site']['uf'] ?>, <?php echo $siteConfig['Site']['cep'] ?></p>
                            </div>
                            <!-- Content Info -->
                            <div class="contact-info">
                                <h5><i class="bi bi-envelope"></i> E-mail</h5>
                                <p><?php echo $siteConfig['Site']['email'] ?></p>
                            </div>
                            <!-- Content Info -->
                            <div class="contact-info">
                                <h5><i class="bi bi-phone"></i> Telefone</h5>
                                <p>+55 <?php echo $siteConfig['Site']['telefone'] ?></p>
                            </div>
                            <!-- Icon -->
                            <ul class="footer-social">
                                <li><a class="facebook" href="https://www.facebook.com/kinder.park.7"><i class="bi bi-facebook"></i></a></li>
                                <li><a class="instagram" href="https://www.instagram.com/kinderpark/"><i class="bi bi-instagram"></i></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>