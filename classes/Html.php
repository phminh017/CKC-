<?php
class Html
{
    public static function tag($tag, $content = "", $opts = [])
    {
        $options =  self::options($opts);
        $strTag = "<" . $tag . $options . ">" . $content . "</" . $tag . ">";
        return $strTag;
    }
    public static function img($src, $opts = [])
    {
        $options =  self::options($opts);
        return "<img src='$src' " . $options . ">";
    }
    static function a($text = '', $url = [], $opts = [])
    {
        $options =  self::options($opts);
        $queryString = new Querystring($url);
        $href = $queryString->toString();
        $strAchor = "<a href='$href'" . $options . ">" . $text . "</a>";
        return $strAchor;
    }
    static function button($text, $opts = [])
    {
        $options =  self::options($opts);
        $strButton = "<button type='button' " . $options . ">" . $text . "</button>";
        return $strButton;
    }
    static function submit($text = "Submit", $opts = [])
    {
        $options =  self::options($opts);
        $strSubmit = "<button type='submit' " . $options . ">" . $text . "</button>";
        return $strSubmit;
    }
    static function textInput($text = "", $opts = [])
    {
        $options =  self::options($opts);
        $str = "<input type='text' value='$text' $options>";
        return $str;
    }
    static function passwordInput($text = "", $opts = [])
    {
        $options =  self::options($opts);
        $str = "<input type='password' value='$text' $options>";
        return $str;
    }
    static function textArea($text = "", $opts = [])
    {
        $options =  self::options($opts);
        $str = "<textarea $options>" . $text . "</textarea>";
        return $str;
    }

    // Active input
    static function activeTextInput($object, $attribute, $opts = [])
    {
        $options =  self::options($opts);
        $className = strtolower(get_class($object));
        $tagId = "$className-$attribute";
        $tagName = ucfirst($className) . "[$attribute]";
        $value = $object->$attribute;
        $str = "";
        $str .= "<input id='$tagId' name='$tagName' type='text' value='$value' spellcheck='false' $options>";
        return $str;
    }
    static function activeTextArea($object, $attribute, $opts = [])
    {
        $options =  self::options($opts);
        $className = strtolower(get_class($object));
        $tagId = "$className-$attribute";
        $tagName = ucfirst($className) . "[$attribute]";
        $value = $object->$attribute;
        $str = "";
        $str .= "<textarea id='$tagId' name='$tagName' spellcheck='false' $options>" . $value . "</textarea>";
        return $str;
    }

    static function activeSelectOptionGroups()
    {
    }
    static function selectOptionGroups($datas = [], $selected = 1, $opts = [])
    {
        $options =  self::options($opts);
        $str = "<select $options>";
        foreach ($datas as $key => $data) {
            $str .= "<optgroup label='$key'>";
            foreach ($data as $key => $name) {
                if ($key == $selected) {
                    $str .= "<option value='$key' selected>" . $name . "</option>";
                } else {
                    $str .= "<option value='$key'>" . $name . "</option>";
                }
            }
            $str .= "</optgroup>";
        }
        $str .= "</select>";
        return $str;
    }
    static function activeSelectOptions($object, $attribute, $data = [], $opts = [])
    {
        $options =  self::options($opts);
        $className = strtolower(get_class($object));
        $tagId = "$className-$attribute";
        $tagName = ucfirst($className) . "[$attribute]";
        $str = "<select id='$tagId' name='$tagName' $options>";
        foreach ($data as $key => $name) {
            if ($object->$attribute == $key) {
                $str .= "<option value='$key' selected>" . $name . "</option>";
            } else {
                $str .= "<option value='$key'>" . $name . "</option>";
            }
        }
        $str .= "</select>";
        return $str;
    }
    static function selectOptions($data = [], $selected = 1, $opts = [])
    {
        $options =  self::options($opts);
        $str = "<select $options>";
        foreach ($data as $key => $name) {
            // $str .= "<option value='$key'>" . $name . "</option>";
            if ($key == $selected) {
                $str .= "<option value='$key' selected>" . $name . "</option>";
            } else {
                $str .= "<option value='$key'>" . $name . "</option>";
            }
        }
        $str .= "</select>";
        return $str;
    }
    static function activeHidden($object, $attribute)
    {
        // <input type="hidden" id="custId" name="custId" value="3487">
        $className = strtolower(get_class($object));
        $tagId = "$className-$attribute";
        $tagName = ucfirst($className) . "[$attribute]";
        $value = $object->$attribute;
        $str = "<input id='$tagId' name='$tagName' type='hidden' value='$value'>";
        return $str;
    }
    static function hidden($value)
    {
        // <input type="hidden" id="custId" name="custId" value="3487">
        $str = "<input type='hidden' value='$value'>";
        return $str;
    }
    static function beginForm($action = [], $method = "post", $opts = [])
    {
        $options =  self::options($opts);
        $queryString = new Querystring($action);
        $href = $queryString->toString();
        $str = "<form action='$href' method='$method' $options>";
        return $str;
    }
    static function endForm()
    {
        return "</form>";
    }
    static function ul($litems = [], $opts = [])
    {
        $options =  self::options($opts);
        $str = "<ul $options>";
        foreach ($litems as $li) {
            $str .= "<li>" . $li . "</li>";
        }
        $str .= "</ul>";
        return $str;
    }

    private static function options($opts = [])
    {
        $str = '';
        foreach ($opts as $key => $value) {
            $str .= ' ';
            $str .= "$key=" . "'$value'";
        }
        return $str;
    }
}
