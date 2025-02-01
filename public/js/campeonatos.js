document.addEventListener("DOMContentLoaded", function() {
    $(document).ready(function() {

        getCountries();
        $('#campeonato').attr('disabled',true);
        $('#paises').change()

        $('#paises').change(function() {

            const paisId = $(this).val();
            const flag = $(this).find(':selected').data('flag');

            $('#bandeira').attr('src', '').attr('alt', '');
            $('#logo-compenato').attr('src', '').attr('alt', '');

           if(flag){
                $('#bandeira').attr('src', flag).attr('alt', $(this).find(':selected').text());
           }

           if(paisId){
                getCompetitions(paisId);
           }
        });

        $('#campeonato').change(function() {
            const campeonatoId = $(this).val();
            const emblem = $(this).find(':selected').data('emblem');

            $('#logo-compenato').attr('src', '').attr('alt', '');

            if(emblem){
                $('#logo-compenato').attr('src', emblem).attr('alt', $(this).find(':selected').text());
            }

            if(campeonatoId){
                getCompetitionData(campeonatoId);
            }
        });

        $('#btn-recarregar-pagina').click(function() {
            window.location.reload();
        });

        function disabledCampeonato(isDisabled) {
            $('#campeonato').attr('disabled',isDisabled);
        }

        function disabledPaises(isDisabled) {
            $('#paises').attr('disabled',isDisabled);
        }

        function getCountries() {
            disabledPaises(true);
            $.ajax({
                url: '/api/countries',
                method: 'GET',
                success: function(response) {
                    let select = $('#paises');
                    select.empty();
                    select.append(
                        '<option value="">Selecione um país</option>'
                    );

                    $.each(response.data, function(index, pais) {
                        var option = new Option(pais.nome, pais.id);
                        option.setAttribute('data-flag', pais.flag);
                        select.append(option);
                    });

                    disabledPaises(false);
                },
                error: function(response) {
                    let select = $('#paises');
                    select.empty();
                    select.append(
                        '<option value="">Erro ao carregar os países</option>'
                    );

                    $('#btn-recarregar-pagina').attr('hidden', false);

                    console.log(response);
                }
            });
        }

        function getCompetitions(idCountrie){
            disabledCampeonato(true);
            $.ajax({
                url: `/api/campeonatos/${idCountrie}/competitionsCountrie`,
                method: 'GET',
                success: function(response) {
                    let select = $('#campeonato');
                    select.empty();
                    select.append(
                        '<option value="">Selecione um campeonato</option>'
                    );

                    $.each(response.data, function(index, campeonato) {
                        var option = new Option(campeonato.name, campeonato.code);
                        option.setAttribute('data-emblem', campeonato.emblem);
                        select.append(option);
                    });

                    disabledCampeonato(false);
                },
                error: function(response) {
                    let select = $('#campeonato');
                    select.empty();
                    select.append(
                        '<option value="">Erro ao carregar os campeonatos</option>'
                    );
                    $('#btn-recarregar-pagina').attr('hidden', false);
                    console.log(response);
                }

            });
        }

        function getCompetitionData(codeCompetition) {
            $.ajax({
                url: `/api/campeonatos/${codeCompetition}/dataCompetition`,
                method: 'GET',
                success: function(response) {
                    console.log(response);
                    let matches = response.data.matchdays;
                    matches.current.forEach(match => {
                        let matchHtml = `
                            <div class="container mb-3 p-3 border rounded">
                                <h6>Rodada: ${match.homeTeam}</h6>
                                <div class="row text-center">
                                    <div class="col"></div>
                                    <div class="col"></div>
                                </div>
                                <div class="row align-items-center text-center">
                                    <div class="col-5">${match.homeTeam} ${1}</div>
                                    <div class="col-2 font-weight-bold">X</div>
                                    <div class="col-5">${match.awayTeam} ${0}</div>
                                </div>
                            </div>
                        `;

                        $(".card-exibe-resulta-competicao").append(matchHtml);
                    });
                },
                error: function(response) {
                    console.log(response);
                }
            });
        }
    });
});
