<?php
SiteHelper::getNavBar($url);
SiteHelper::getBackyardBreadcrumbs($url);
$similar_artist_god_obj = new SimilarArtistGod();
$similar_artist_arbor_code = $similar_artist_god_obj->getSimilarArtistArborCode();
$similar_artist_source_array = $similar_artist_god_obj->getSimilarArtistSourceArray();
unset($similar_artist_god_obj);
?>
<div style="width: 1200px; margin: 0 auto;">
    <div style="border: 2px solid gray; width: 360px; height: 800px;" class="pull-left">
        <div style="height: 27px; border-bottom: 2px solid gray;">
            <table class="table table-condensed">
                <thead></thead>
                <tbody>
                    <tr>
                        <td style="width: 15px;">
                            <input type="checkbox" id="toggle-checkbox-all" />
                        </td>
                        <td style="width: 50px;">ID</td>
                        <td>Name</td>
                        <td style="width: 15px;">Edges</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div style=" overflow-y: scroll; height: 770px;">
            <table class="table table-condensed table-bordered table-striped">
                <thead></thead>
                <tbody>
                    <?php
                    foreach ($similar_artist_source_array as $artist_id => $data) {

                        if ($data['title'] != ' ' && $data['edges'] < $data['fans']) {
                    ?>
                    <tr>
                        <td style="width: 15px;"><input type="checkbox" value="<?php echo $artist_id; ?>" class="toggle-checkbox" /></td>
                        <td style="width: 50px;"><?php echo $artist_id; ?></td>
                        <td><?php echo $data['title']; ?></td>
                        <td style="width: 15px; text-align: right;"><?php echo $data['edges']; ?></td>
                    </tr>
                    <?php
                        }

                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
    <div id="halfviz" class="pull-left" style="border: 3px solid black; width: 800px; height: 800px; margin-left: 30px;">
        <canvas id="viewport" width="800" height="800" style="background-color: #333333;"></canvas>
        <div id="editor" style="display: none;">
          <textarea id="code"><?php echo $similar_artist_arbor_code; ?></textarea>  
        </div>
    </div>
</div>
<script src="/_asset/js/arbor/jquery.address-1.4.min.js"></script>
<script src="/_asset/js/arbor/arbor.js"></script>
<script src="/_asset/js/arbor/graphics.js"></script>
<script src="/_asset/js/arbor/dashboard.js"></script>
<script src="/_asset/js/arbor/help.js"></script>
<!-- <script src="/_asset/js/arbor/io.js"></script> -->
<script src="/_asset/js/arbor/parseur.js"></script>
<script src="/_asset/js/arbor/renderer.js"></script>
<script>
$('#halfviz').ready(function () {

    trace = arbor.etc.trace
    objmerge = arbor.etc.objmerge
    objcopy = arbor.etc.objcopy
    var parse = Parseur().parse

    var SimilarArtist = function(elt){
        var dom = $(elt)

        //repulsion, tension, friction, align-center
        sys = arbor.ParticleSystem(1000, 100, 0.5, true)
        sys.renderer = Renderer("#viewport") // our newly created renderer will have its .init() method called shortly by sys...
        sys.screenPadding(20)

        var _ed = dom.find('#editor')
        var _code = dom.find('textarea')
        var _canvas = dom.find('#viewport').get(0)
        var _grabber = dom.find('#grabber')

        var _updateTimeout = null
        var _current = null // will be the id of the doc if it's been saved before
        var _editing = false // whether to undim the Save menu and prevent navigating away
        var _failures = null
        var that = {

            dashboard:Dashboard("#dashboard", sys),
            // io:IO("#editor .io"),
            init:function(){

                $(window).resize(that.resize)
                that.resize()
                //that.updateLayout(Math.max(1, $(window).width()-340))

                _code.keydown(that.typing)
                _grabber.bind('mousedown', that.grabbed)

                $(that.io).bind('get', that.getDoc)
                $(that.io).bind('clear', that.newDoc)
                return that
            },

            getDoc:function(e){
                $.getJSON('library/'+e.id+'.json', function(doc){

                    // update the system parameters
                    if (doc.sys){
                        sys.parameters(doc.sys)
                        that.dashboard.update()
                    }

                    // modify the graph in the particle system
                    _code.val(doc.src)
                    that.updateGraph()
                    that.resize()
                    _editing = false
                })

            },

            newDoc:function(){
                var lorem = "; some example nodes\nhello {color:red, label:HELLO}\nworld {color:orange}\n\n; some edges\nhello -> world {color:yellow}\nfoo -> bar {weight:5}\nbar -> baz {weight:2}"

                _code.val(lorem).focus()
                $.address.value("")
                that.updateGraph()
                that.resize()
                _editing = false
            },

            updateGraph:function(e){
                var src_txt = _code.val()
                var network = parse(src_txt)
                $.each(network.nodes, function(nname, ndata){
                    if (ndata.label===undefined) ndata.label = nname
                })
                sys.merge(network)
                _updateTimeout = null
            },

            resize:function(){
                // var w = $(window).width() - 40
                // var x = w - _ed.width()
                // that.updateLayout(x)
                // sys.renderer.redraw()
            },

            updateLayout:function(split){
                var w = dom.width()
                var h = _grabber.height()
                var split = split || _grabber.offset().left
                var splitW = _grabber.width()
                _grabber.css('left',split)

                var edW = w - split
                var edH = h
                _ed.css({width:edW, height:edH})
                if (split > w-20) _ed.hide()
                else _ed.show()

                var canvW = split - splitW
                var canvH = h
                _canvas.width = canvW
                _canvas.height = canvH
                sys.screenSize(canvW, canvH)

                _code.css({height:h-20,  width:edW-4, marginLeft:2})
            },

            grabbed:function(e){
                $(window).bind('mousemove', that.dragged)
                $(window).bind('mouseup', that.released)
                return false
            },
            dragged:function(e){
                var w = dom.width()
                that.updateLayout(Math.max(10, Math.min(e.pageX-10, w)) )
                sys.renderer.redraw()
                return false
            },
            released:function(e){
                $(window).unbind('mousemove', that.dragged)
                return false
            },
            typing:function(e){
                var c = e.keyCode
                if ($.inArray(c, [37, 38, 39, 40, 16])>=0){
                    return
                }

                if (!_editing){
                    $.address.value("")
                }
                _editing = true

                if (_updateTimeout) clearTimeout(_updateTimeout)
                _updateTimeout = setTimeout(that.updateGraph, 900)
            }
        }

        return that.init()
    }

    var mcp = SimilarArtist("#halfviz");
    mcp.updateGraph();

    function show_user(user_id) {

        if (user_id == 'all') {
            var re = /;user-(.*)-\n;/g;
        } else {
            var re = new RegExp(";user-"+user_id+"-\n;", 'g');
        }

        $('#code').val($('#code').val().replace(re, function (word) {
            return word.substr(0, word.length - 1);
        }));
        mcp.updateGraph();

    }

    function hide_user(user_id) {

        if (user_id == 'all') {
            var re = /;user-(.*)-\n(?!;)/g;
        } else {
            var re = new RegExp(";user-"+user_id+"-\n(?!;)", 'g');
        }

        $('#code').val($('#code').val().replace(re, "$&;"));
        mcp.updateGraph();

    }
   
    $(document.body).off('click', '.toggle-checkbox');
    $(document.body).on('click', '.toggle-checkbox', function () {

        var user_id = $(this).val();
        if ($(this).is(":checked")) {

            show_user(user_id);

        } else {

            hide_user(user_id);

        }

    });
   
    $(document.body).off('click', '#toggle-checkbox-all');
    $(document.body).on('click', '#toggle-checkbox-all', function () {

        if ($(this).is(":checked")) {

            $('.toggle-checkbox:not(:checked)').attr('checked', 'checked');
            show_user('all');

        } else {

            $('.toggle-checkbox:checked').removeAttr('checked');
            hide_user('all');

        }

    });

});
</script>