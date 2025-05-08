<?php
function pretty_print( $json )
{
    $result = '';
    $level = 0;
    $in_quotes = false;
    $in_escape = false;
    $ends_line_level = NULL;
    $json_length = strlen( $json );

    for( $i = 0; $i < $json_length; $i++ ) {
        $char = $json[$i];
        $new_line_level = NULL;
        $post = "";
        if( $ends_line_level !== NULL ) {
            $new_line_level = $ends_line_level;
            $ends_line_level = NULL;
        }
        if ( $in_escape ) {
            $in_escape = false;
        } else if( $char === '"' ) {
            $in_quotes = !$in_quotes;
        } else if( ! $in_quotes ) {
            switch( $char ) {
                case '}': case ']':
                    $level--;
                    $ends_line_level = NULL;
                    $new_line_level = $level;
                    break;

                case '{': case '[':
                    $level++;
                case ',':
                    $ends_line_level = $level;
                    break;

                case ':':
                    $post = " ";
                    break;

                case " ": case "\t": case "\n": case "\r":
                    $char = "";
                    $ends_line_level = $new_line_level;
                    $new_line_level = NULL;
                    break;
            }
        } else if ( $char === '\\' ) {
            $in_escape = true;
        }
        if( $new_line_level !== NULL ) {
            $result .= "\n".str_repeat( "\t", $new_line_level );
        }
        $result .= $char.$post;
    }

    return $result;
}


/**
 * Contact Form 7 custom validation
 */
// function custom_iban_validation_filter($result, $tag) {

//     // Specify the name of the field you want to validate
//     $name = $tag->name;

//     if ($name == 'iban') {
//         // Get the value submitted in the field
//         $value = isset($_POST[$name]) ? trim($_POST[$name]) : '';

//         if (!validate_iban($value)) {
//             // Set the validation error message
//             $result->invalidate($tag, "The IBAN code you entered is not valid.");
//         }
//     }

//     return $result;
// }
// add_filter('wpcf7_validate_text*', 'custom_iban_validation_filter', 10, 2);
// add_filter('wpcf7_validate_text', 'custom_iban_validation_filter', 10, 2);



// function validate_iban($iban) {
//     // Normalize the input by removing spaces and converting to uppercase
//     $iban = strtoupper(str_replace(' ', '', $iban));

//     // The first two characters must be letters, and the next two must be digits
//     if (!preg_match('/^[A-Z]{2}\d{2}/', $iban)) {
//         return false;
//     }

//     // Move the first four characters to the end of the string
//     $iban = substr($iban, 4) . substr($iban, 0, 4);

//     // Convert letters to numbers (A = 10, B = 11, ..., Z = 35)
//     $iban = str_replace(
//         range('A', 'Z'),
//         range(10, 35),
//         $iban
//     );

//     // Convert the string to an integer and calculate the remainder of the division by 97
//     if (my_bcmod($iban, 97) != 1) {
//         return false;
//     }

//     return false;
// }