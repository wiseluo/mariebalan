/**
 * Created by chen on 2017/11/2.
 */
WX_Platform_Load([

], function() {
    $(function(){
        var i = Math.floor(Math.random() * 29),
            loadArr = [a1 = '<div id=' + '"' + 'loading-center-absolute1' + '"' + 'class=' + '"' + ' ' + '"' + '>' +
                '<div id=' + '"' + 'object1' + '"' + '></div>' +
                '</div>',
                a2 = '<div id=' + '"' + 'loading-center-absolute2' + '"' + '>' +
                    '<div class=' + '"' + 'object2' + '"' + 'id=' + '"' + 'object_one2' + '"' + '></div>' +
                    '<div class=' + '"' + 'object2' + '"' + 'id=' + '"' + 'object_two2' + '"' + '></div>' +
                    '<div class=' + '"' + 'object2' + '"' + 'id=' + '"' + 'object_three2' + '"' + '></div>' +
                    '<div class=' + '"' + 'object2' + '"' + 'id=' + '"' + 'object_four2' + '"' + '></div>' +
                    '<div class=' + '"' + 'object2' + '"' + 'id=' + '"' + 'object_five2' + '"' + '></div>' +
                    '<div class=' + '"' + 'object2' + '"' + 'id=' + '"' + 'object_six2' + '"' + '></div>' +
                    '<div class=' + '"' + 'object2' + '"' + 'id=' + '"' + 'object_seven2' + '"' + '></div>' +
                    '<div class=' + '"' + 'object2' + '"' + 'id=' + '"' + 'object_eight2' + '"' + '></div>' +
                    '<div class=' + '"' + 'object2' + '"' + 'id=' + '"' + 'object_big2' + '"' + '></div>' +
                    '</div>',
                a3 = '<div id=' + '"' + 'loading-center-absolute3' + '"' + '>' +
                    '<div class=' + '"' + 'object3' + '"' + 'id=' + '"' + 'first_object3' + '"' + '></div>' +
                    '<div class=' + '"' + 'object3' + '"' + 'id=' + '"' + 'second_object3' + '"' + '></div>' +
                    '<div class=' + '"' + 'object3' + '"' + 'id=' + '"' + 'third_object3' + '"' + '></div>' +
                    '</div>',
                a4 = '<div id=' + '"' + 'loading-center-absolute4' + '"' + '>' +
                    '<div class=' + '"' + 'object4' + '"' + 'id=' + '"' + 'first_object4' + '"' + '></div>' +
                    '<div class=' + '"' + 'object4' + '"' + 'id=' + '"' + 'second_object4' + '"' + '></div>' +
                    '<div class=' + '"' + 'object4' + '"' + 'id=' + '"' + 'third_object4' + '"' + '></div>' +
                    '<div class=' + '"' + 'object4' + '"' + 'id=' + '"' + 'forth_object4' + '"' + '></div>' +
                    '</div>',
                a5 = '<div id=' + '"' + 'loading-center-absolute5' + '"' + '>' +
                    '<div class=' + '"' + 'object5' + '"' + 'id=' + '"' + 'object_one5' + '"' + '></div>' +
                    '<div class=' + '"' + 'object5' + '"' + 'id=' + '"' + 'object_two5' + '"' + '></div>' +
                    '<div class=' + '"' + 'object5' + '"' + 'id=' + '"' + 'object_three5' + '"' + '></div>' +
                    '<div class=' + '"' + 'object5' + '"' + 'id=' + '"' + 'object_four5' + '"' + '></div>' +
                    '<div class=' + '"' + 'object5' + '"' + 'id=' + '"' + 'object_five5' + '"' + '></div>' +
                    '<div class=' + '"' + 'object5' + '"' + 'id=' + '"' + 'object_six5' + '"' + '></div>' +
                    '<div class=' + '"' + 'object5' + '"' + 'id=' + '"' + 'object_seven5' + '"' + '></div>' +
                    '<div class=' + '"' + 'object5' + '"' + 'id=' + '"' + 'object_eight5' + '"' + '></div>' +
                    '<div class=' + '"' + 'object5' + '"' + 'id=' + '"' + 'object_nine5' + '"' + '></div>' +
                    '</div>',
                a6 = '<div id=' + '"' + 'loading-center-absolute6' + '"' + '>' +
                    '<div class=' + '"' + 'object6' + '"' + 'id=' + '"' + 'object_one6' + '"' + '></div>' +
                    '<div class=' + '"' + 'object6' + '"' + 'id=' + '"' + 'object_two6' + '"' + '></div>' +
                    '<div class=' + '"' + 'object6' + '"' + 'id=' + '"' + 'object_three6' + '"' + '></div>' +
                    '<div class=' + '"' + 'object6' + '"' + 'id=' + '"' + 'object_four6' + '"' + '></div>' +
                    '<div class=' + '"' + 'object6' + '"' + 'id=' + '"' + 'object_five6' + '"' + '></div>' +
                    '<div class=' + '"' + 'object6' + '"' + 'id=' + '"' + 'object_six6' + '"' + '></div>' +
                    '</div>',
                a7 = '<div id=' + '"' + 'loading-center-absolute7' + '"' + '>' +
                    '<div class=' + '"' + 'object7' + '"' + 'id=' + '"' + 'object_one7' + '"' + '></div>' +
                    '<div class=' + '"' + 'object7' + '"' + 'id=' + '"' + 'object_two7' + '"' + '></div>' +
                    '<div class=' + '"' + 'object7' + '"' + 'id=' + '"' + 'object_three7' + '"' + '></div>' +
                    '<div class=' + '"' + 'object7' + '"' + 'id=' + '"' + 'object_four7' + '"' + '></div>' +
                    '</div>',
                a8 = '<div id=' + '"' + 'loading-center-absolute8' + '"' + '>' +
                    '<div class=' + '"' + 'object8' + '"' + 'id=' + '"' + 'object_one8' + '"' + '></div>' +
                    '<div class=' + '"' + 'object8' + '"' + 'id=' + '"' + 'object_two8' + '"' + '></div>' +
                    '<div class=' + '"' + 'object8' + '"' + 'id=' + '"' + 'object_three8' + '"' + '></div>' +
                    '<div class=' + '"' + 'object8' + '"' + 'id=' + '"' + 'object_four8' + '"' + '></div>' +
                    '</div>',
                a9 = '<div id=' + '"' + 'loading-center-absolute9' + '"' + '>' +
                    '<div class=' + '"' + 'object9' + '"' + 'id=' + '"' + 'object_one9' + '"' + '></div>' +
                    '<div class=' + '"' + 'object9' + '"' + 'id=' + '"' + 'object_two9' + '"' + '></div>' +
                    '<div class=' + '"' + 'object9' + '"' + 'id=' + '"' + 'object_three9' + '"' + '></div>' +
                    '<div class=' + '"' + 'object9' + '"' + 'id=' + '"' + 'object_four9' + '"' + '></div>' +
                    '<div class=' + '"' + 'object9' + '"' + 'id=' + '"' + 'object_big9' + '"' + '></div>' +
                    '</div>',
                a10 = '<div id=' + '"' + 'loading-center-absolute10' + '"' + '>' +
                    '<div id=' + '"' + 'loading-center-absolute-one10' + '"' + '>' +
                    '<div class=' + '"' + 'object-one10' + '"' + '></div>' +
                    '<div class=' + '"' + 'object-one10' + '"' + '></div>' +
                    '<div class=' + '"' + 'object-one10' + '"' + '></div>' +
                    '<div class=' + '"' + 'object-one10' + '"' + '></div>' +
                    '<div class=' + '"' + 'object-one10' + '"' + '></div>' +
                    '<div class=' + '"' + 'object-one10' + '"' + '></div>' +
                    '</div>' +
                    '<div id=' + '"' + 'loading-center-absolute-two10' + '"' + '>' +
                    '<div class=' + '"' + 'object-two10' + '"' + '></div>' +
                    '<div class=' + '"' + 'object-two10' + '"' + '></div>' +
                    '<div class=' + '"' + 'object-two10' + '"' + '></div>' +
                    '<div class=' + '"' + 'object-two10' + '"' + '></div>' +
                    '<div class=' + '"' + 'object-two10' + '"' + '></div>' +
                    '<div class=' + '"' + 'object-two10' + '"' + '></div>' +
                    '</div>' +
                    '</div>',
                a11 = '<div id=' + '"' + 'loading-center-absolute11' + '"' + '>' +
                    '<div class=' + '"' + 'object11' + '"' + 'id=' + '"' + 'object_one11' + '"' + '></div>' +
                    '<div class=' + '"' + 'object11' + '"' + 'id=' + '"' + 'object_two11' + '"' + '></div>' +
                    '<div class=' + '"' + 'object11' + '"' + 'id=' + '"' + 'object_three11' + '"' + '></div>' +
                    '</div>',
                a12 = '<div id=' + '"' + 'loading-center-absolute12' + '"' + '>' +
                    '<div class=' + '"' + 'object12' + '"' + 'id=' + '"' + 'object_one12' + '"' + '></div>' +
                    '<div class=' + '"' + 'object12' + '"' + 'id=' + '"' + 'object_two12' + '"' + '></div>' +
                    '<div class=' + '"' + 'object12' + '"' + 'id=' + '"' + 'object_three12' + '"' + '></div>' +
                    '<div class=' + '"' + 'object12' + '"' + 'id=' + '"' + 'object_four12' + '"' + '></div>' +
                    '<div class=' + '"' + 'object12' + '"' + 'id=' + '"' + 'object_five12' + '"' + '></div>' +
                    '<div class=' + '"' + 'object12' + '"' + 'id=' + '"' + 'object_six12' + '"' + '></div>' +
                    '<div class=' + '"' + 'object12' + '"' + 'id=' + '"' + 'object_seven12' + '"' + '></div>' +
                    '<div class=' + '"' + 'object12' + '"' + 'id=' + '"' + 'object_eight12' + '"' + '></div>' +
                    '<div class=' + '"' + 'object12' + '"' + 'id=' + '"' + 'object_big12' + '"' + '></div>' +
                    '</div>',
                a13 = '<div id=' + '"' + 'loading-center-absolute13' + '"' + '>' +
                    '<div class=' + '"' + 'object13' + '"' + 'id=' + '"' + 'first_object13' + '"' + '></div>' +
                    '<div class=' + '"' + 'object13' + '"' + 'id=' + '"' + 'second_object13' + '"' + '></div>' +
                    '</div>',
                a14 = '<div id=' + '"' + 'loading-center-absolute14' + '"' + '>' +
                    '<div class=' + '"' + 'object14' + '"' + '></div>' +
                    '<div class=' + '"' + 'object14' + '"' + '></div>' +
                    '<div class=' + '"' + 'object14' + '"' + '></div>' +
                    '<div class=' + '"' + 'object14' + '"' + '></div>' +
                    '<div class=' + '"' + 'object14' + '"' + '></div>' +
                    '<div class=' + '"' + 'object14' + '"' + '></div>' +
                    '<div class=' + '"' + 'object14' + '"' + '></div>' +
                    '<div class=' + '"' + 'object14' + '"' + '></div>' +
                    '<div class=' + '"' + 'object14' + '"' + '></div>' +
                    '</div>',
                a15 = '<div id=' + '"' + 'loading-center-absolute15' + '"' + '>' +
                    '<div id=' + '"' + 'loading-center-absolute-one15' + '"' + '>' +
                    '<div class=' + '"' + 'object-one15' + '"' + '></div>' +
                    '<div class=' + '"' + 'object-one15' + '"' + '></div>' +
                    '<div class=' + '"' + 'object-one15' + '"' + '></div>' +
                    '<div class=' + '"' + 'object-one15' + '"' + '></div>' +
                    '<div class=' + '"' + 'object-one15' + '"' + '></div>' +
                    '<div class=' + '"' + 'object-one15' + '"' + '></div>' +
                    '<div class=' + '"' + 'object-one15' + '"' + '></div>' +
                    '<div class=' + '"' + 'object-one15' + '"' + '></div>' +
                    '<div class=' + '"' + 'object-one15' + '"' + '></div>' +
                    '</div>' +
                    '<div id=' + '"' + 'loading-center-absolute-two15' + '"' + '>' +
                    '<div class=' + '"' + 'object-two15' + '"' + '></div>' +
                    '<div class=' + '"' + 'object-two15' + '"' + '></div>' +
                    '<div class=' + '"' + 'object-two15' + '"' + '></div>' +
                    '<div class=' + '"' + 'object-two15' + '"' + '></div>' +
                    '<div class=' + '"' + 'object-two15' + '"' + '></div>' +
                    '<div class=' + '"' + 'object-two15' + '"' + '></div>' +
                    '<div class=' + '"' + 'object-two15' + '"' + '></div>' +
                    '<div class=' + '"' + 'object-two15' + '"' + '></div>' +
                    '<div class=' + '"' + 'object-two15' + '"' + '></div>' +
                    '</div>' +
                    '</div>',
                a16 = '<div id=' + '"' + 'loading-center-absolute16' + '"' + '>' +
                    '<div class=' + '"' + 'object16' + '"' + 'id=' + '"' + 'object_one16' + '"' + '></div>' +
                    '<div class=' + '"' + 'object16' + '"' + 'id=' + '"' + 'object_two16' + '"' + '></div>' +
                    '<div class=' + '"' + 'object16' + '"' + 'id=' + '"' + 'object_three16' + '"' + '></div>' +
                    '<div class=' + '"' + 'object16' + '"' + 'id=' + '"' + 'object_four16' + '"' + '></div>' +
                    '</div>',
                a17 = '<div id=' + '"' + 'loading-center-absolute17' + '"' + '>' +
                    '<div class=' + '"' + 'object17' + '"' + 'id=' + '"' + 'object_one17' + '"' + '></div>' +
                    '<div class=' + '"' + 'object17' + '"' + 'id=' + '"' + 'object_two17' + '"' + '></div>' +
                    '<div class=' + '"' + 'object17' + '"' + 'id=' + '"' + 'object_three17' + '"' + '></div>' +
                    '<div class=' + '"' + 'object17' + '"' + 'id=' + '"' + 'object_four17' + '"' + '></div>' +
                    '</div>',
                a18 = '<div id=' + '"' + 'loading-center-absolute18' + '"' + '>' +
                    '<div class=' + '"' + 'object18' + '"' + 'id=' + '"' + 'object_one18' + '"' + '></div>' +
                    '<div class=' + '"' + 'object18' + '"' + 'id=' + '"' + 'object_two18' + '"' + '></div>' +
                    '<div class=' + '"' + 'object18' + '"' + 'id=' + '"' + 'object_three18' + '"' + '></div>' +
                    '<div class=' + '"' + 'object18' + '"' + 'id=' + '"' + 'object_four18' + '"' + '></div>' +
                    '<div class=' + '"' + 'object18' + '"' + 'id=' + '"' + 'object_five18' + '"' + '></div>' +
                    '</div>',
                a19 = '<div id=' + '"' + 'loading-center-absolute19' + '"' + '>' +
                    '<div class=' + '"' + 'object19' + '"' + 'id=' + '"' + 'object_one19' + '"' + '></div>' +
                    '<div class=' + '"' + 'object19' + '"' + 'id=' + '"' + 'object_two19' + '"' + '></div>' +
                    '<div class=' + '"' + 'object19' + '"' + 'id=' + '"' + 'object_three19' + '"' + '></div>' +
                    '<div class=' + '"' + 'object19' + '"' + 'id=' + '"' + 'object_four19' + '"' + '></div>' +
                    '<div class=' + '"' + 'object19' + '"' + 'id=' + '"' + 'object_five19' + '"' + '></div>' +
                    '<div class=' + '"' + 'object19' + '"' + 'id=' + '"' + 'object_six19' + '"' + '></div>' +
                    '<div class=' + '"' + 'object19' + '"' + 'id=' + '"' + 'object_seven19' + '"' + '></div>' +
                    '<div class=' + '"' + 'object19' + '"' + 'id=' + '"' + 'object_eight19' + '"' + '></div>' +
                    '<div class=' + '"' + 'object19' + '"' + 'id=' + '"' + 'object_nine19' + '"' + '></div>' +
                    '</div>',
                a20 = '<div id=' + '"' + 'loading-center-absolute20' + '"' + '>' +
                    '<div class=' + '"' + 'object20' + '"' + 'id=' + '"' + 'object_one20' + '"' + '></div>' +
                    '<div class=' + '"' + 'object20' + '"' + 'id=' + '"' + 'object_two20' + '"' + '></div>' +
                    '<div class=' + '"' + 'object20' + '"' + 'id=' + '"' + 'object_three20' + '"' + '></div>' +
                    '<div class=' + '"' + 'object20' + '"' + 'id=' + '"' + 'object_four20' + '"' + '></div>' +
                    '<div class=' + '"' + 'object20' + '"' + 'id=' + '"' + 'object_five20' + '"' + '></div>' +
                    '<div class=' + '"' + 'object20' + '"' + 'id=' + '"' + 'object_six20' + '"' + '></div>' +
                    '<div class=' + '"' + 'object20' + '"' + 'id=' + '"' + 'object_seven20' + '"' + '></div>' +
                    '<div class=' + '"' + 'object20' + '"' + 'id=' + '"' + 'object_eight20' + '"' + '></div>' +
                    '</div>',
                a21 = '<div id=' + '"' + 'loading-center-absolute21' + '"' + '>' +
                    '<div class=' + '"' + 'object21' + '"' + 'id=' + '"' + 'object_one21' + '"' + '></div>' +
                    '<div class=' + '"' + 'object21' + '"' + 'id=' + '"' + 'object_two21' + '"' + '></div>' +
                    '<div class=' + '"' + 'object21' + '"' + 'id=' + '"' + 'object_three21' + '"' + '></div>' +
                    '</div>',
                a22 = '<div id=' + '"' + 'loading-center-absolute22' + '"' + '>' +
                    '<div class=' + '"' + 'object22' + '"' + 'id=' + '"' + 'object_one22' + '"' + '></div>' +
                    '<div class=' + '"' + 'object22' + '"' + 'id=' + '"' + 'object_two22' + '"' + '></div>' +
                    '<div class=' + '"' + 'object22' + '"' + 'id=' + '"' + 'object_three22' + '"' + '></div>' +
                    '<div class=' + '"' + 'object22' + '"' + 'id=' + '"' + 'object_four22' + '"' + '></div>' +
                    '</div>',
                a23 = '<div id=' + '"' + 'loading-center-absolute23' + '"' + '>' +
                    '<div class=' + '"' + 'object23' + '"' + 'id=' + '"' + 'object_one23' + '"' + '></div>' +
                    '</div>',
                a24 = '<div id=' + '"' + 'loading-center-absolute24' + '"' + '>' +
                    '<div class=' + '"' + 'object24' + '"' + '></div>' +
                    '<div class=' + '"' + 'object24' + '"' + '></div>' +
                    '<div class=' + '"' + 'object24' + '"' + '></div>' +
                    '<div class=' + '"' + 'object24' + '"' + '></div>' +
                    '<div class=' + '"' + 'object24' + '"' + '></div>' +
                    '<div class=' + '"' + 'object24' + '"' + '></div>' +
                    '<div class=' + '"' + 'object24' + '"' + '></div>' +
                    '<div class=' + '"' + 'object24' + '"' + '></div>' +
                    '<div class=' + '"' + 'object24' + '"' + '></div>' +
                    '<div class=' + '"' + 'object24' + '"' + '></div>' +
                    '</div>',
                a25 = '<div id=' + '"' + 'loading-center-absolute25' + '"' + '>' +
                    '<div class=' + '"' + 'object25' + '"' + '></div>' +
                    '<div class=' + '"' + 'object25' + '"' + '></div>' +
                    '<div class=' + '"' + 'object25' + '"' + '></div>' +
                    '<div class=' + '"' + 'object25' + '"' + '></div>' +
                    '<div class=' + '"' + 'object25' + '"' + '></div>' +
                    '<div class=' + '"' + 'object25' + '"' + '></div>' +
                    '<div class=' + '"' + 'object25' + '"' + '></div>' +
                    '<div class=' + '"' + 'object25' + '"' + '></div>' +
                    '<div class=' + '"' + 'object25' + '"' + '></div>' +
                    '<div class=' + '"' + 'object25' + '"' + '></div>' +
                    '</div>',
                a26 = '<div id=' + '"' + 'loading-center-absolute26' + '"' + '>' +
                    '<div class=' + '"' + 'object26' + '"' + '></div>' +
                    '<div class=' + '"' + 'object26' + '"' + '></div>' +
                    '<div class=' + '"' + 'object26' + '"' + '></div>' +
                    '<div class=' + '"' + 'object26' + '"' + '></div>' +
                    '<div class=' + '"' + 'object26' + '"' + '></div>' +
                    '<div class=' + '"' + 'object26' + '"' + '></div>' +
                    '<div class=' + '"' + 'object26' + '"' + '></div>' +
                    '<div class=' + '"' + 'object26' + '"' + '></div>' +
                    '<div class=' + '"' + 'object26' + '"' + '></div>' +
                    '<div class=' + '"' + 'object26' + '"' + '></div>' +
                    '</div>',
                a27 = '<div id=' + '"' + 'loading-center-absolute27' + '"' + '>' +
                    '<div class=' + '"' + 'object27' + '"' + 'id=' + '"' + 'object_one27' + '"' + '></div>' +
                    '<div class=' + '"' + 'object27' + '"' + 'id=' + '"' + 'object_two27' + '"' + '></div>' +
                    '<div class=' + '"' + 'object27' + '"' + 'id=' + '"' + 'object_three27' + '"' + '></div>' +
                    '<div class=' + '"' + 'object27' + '"' + 'id=' + '"' + 'object_four27' + '"' + '></div>' +
                    '</div>',
                a28 = '<div id=' + '"' + 'loading-center-absolute28' + '"' + '>' +
                    '<div class=' + '"' + 'object28' + '"' + 'id=' + '"' + 'object_four28' + '"' + '></div>' +
                    '<div class=' + '"' + 'object28' + '"' + 'id=' + '"' + 'object_three28' + '"' + '></div>' +
                    '<div class=' + '"' + 'object28' + '"' + 'id=' + '"' + 'object_two28' + '"' + '></div>' +
                    '<div class=' + '"' + 'object28' + '"' + 'id=' + '"' + 'object_one28' + '"' + '></div>' +
                    '</div>',
                a29 = '<div id=' + '"' + 'loading-center-absolute26' + '"' + '>' +
                    '<div class=' + '"' + 'object29' + '"' + '></div>' +
                    '<div class=' + '"' + 'object29' + '"' + '></div>' +
                    '<div class=' + '"' + 'object29' + '"' + '></div>' +
                    '<div class=' + '"' + 'object29' + '"' + '></div>' +
                    '<div class=' + '"' + 'object29' + '"' + '></div>' +
                    '<div class=' + '"' + 'object29' + '"' + '></div>' +
                    '<div class=' + '"' + 'object29' + '"' + '></div>' +
                    '<div class=' + '"' + 'object29' + '"' + '></div>' +
                    '<div class=' + '"' + 'object29' + '"' + '></div>' +
                    '<div class=' + '"' + 'object29' + '"' + '></div>' +
                    '<div class=' + '"' + 'object29' + '"' + '></div>' +
                    '<div class=' + '"' + 'object29' + '"' + '></div>' +
                    '<div class=' + '"' + 'object29' + '"' + '></div>' +
                    '<div class=' + '"' + 'object29' + '"' + '></div>' +
                    '<div class=' + '"' + 'object29' + '"' + '></div>' +
                    '<div class=' + '"' + 'object29' + '"' + '></div>' +
                    '<div class=' + '"' + 'object29' + '"' + '></div>' +
                    '<div class=' + '"' + 'object29' + '"' + '></div>' +
                    '<div class=' + '"' + 'object29' + '"' + '></div>' +
                    '<div class=' + '"' + 'object29' + '"' + '></div>' +
                    '</div>'];
        $('#loading-center').append(loadArr[i]);
    })
})