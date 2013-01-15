<html>
<head>
        <script type="text/javascript" src="jquery.qtip-1.0.0-rc3.min.js"></script>


        <script type="text/javascript">
        $( document ).ready( function( ) {
            $.fn.qtip.styles.tooltipDefault = {
                background  : '#132531',
                color       : '#FFFFFF',
                textAlign   : 'left',
                border      : {
                    width   : 2,
                    radius  : 4,
                    color   : '#C1CFDD'
                },
                width       : 220
            }

            // we are going to run through each element with the classRating class
            $( '.classRating' ).each( function( ) {
                var rating = $( this ).attr( 'rating' );

                // the element has no rating tag or the rating tag is empty
                if ( rating == undefined || rating == '' ) {
                    rating = 'I have not yet been rated.';
                }
                else {
                    rating = 'The rating for this is ' + rating + '%';
                }

                // create the tooltip for the current element
                $( this ).qtip( {
                    content     : rating,
                    position    : {
                        target  : 'mouse'
                    },
                    style       : 'tooltipDefault'
                } );
            } );
        } );

        </script>
</head>
<body>
    <div id="SomeID1" class="classRating" rating="73">I have a rating</div>
    <div id="SomeID2" class="classRating" rating="66">I have a rating</div>
    <div id="SomeID3" class="classRating" rating="">I have a rating but it is empty</div>
    <div id="SomeID4" class="classRating">I dont have a rating</div>
</body>
</html>
