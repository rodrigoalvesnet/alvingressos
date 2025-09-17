<section id="google-map-area">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 padding-0">
                <?php if (!isset($unidade) || $unidade == 'matriz') { ?>
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3604.0954867882624!2d-49.218441923706386!3d-25.40161413177099!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x94dce45d47be3c51%3A0xa7131975e453d52f!2sKinder%20Park!5e0!3m2!1sen!2sus!4v1757541130324!5m2!1sen!2sus" width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                <?php } ?>
                <?php if (isset($unidade) && $unidade == 'itapema') { ?>
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d1775.4662871776375!2d-48.60427536107689!3d-27.1269336069665!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x94d8ae2b04e46ea9%3A0x5d390322b3178bba!2sKinder%20Park!5e0!3m2!1sen!2sus!4v1757541273963!5m2!1sen!2sus" width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                <?php } ?>
                <?php if (isset($unidade) && $unidade == 'colombo') { ?>
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3605.3096358400176!2d-49.18754842370751!3d-25.360937130164245!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x94dce90e232ba31f%3A0x22cb96778acff5ab!2sKinder%20Park%20-%20Colombo!5e0!3m2!1sen!2sus!4v1757541222324!5m2!1sen!2sus" width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                <?php } ?>
                <?php if (isset($unidade) && $unidade == 'pinheirinho') { ?>
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d7201.874726292812!2d-49.28907822370323!3d-25.507135435949895!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x94dcfca7a97c5425%3A0x2475688904e5d238!2sR.%20Ipiranga%2C%20960%20-%20Pinheirinho%2C%20Curitiba%20-%20PR%2C%2081110-521%2C%20Brazil!5e0!3m2!1sen!2sus!4v1757541379417!5m2!1sen!2sus" width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                <?php } ?>
                <?php if (isset($unidade) && $unidade == 'joinville') { ?>
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3576.779761502328!2d-48.85405251256668!3d-26.301238808659452!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x94deb03799e8e3d7%3A0xb2f9e6a79fe260c6!2sR.%20M%C3%A1rio%20Lobo%2C%20106%20-%20Centro%2C%20Joinville%20-%20SC%2C%2089201-330%2C%20Brazil!5e0!3m2!1sen!2sus!4v1757541335877!5m2!1sen!2sus" width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                <?php } ?>
            </div>
        </div>
    </div>
</section>