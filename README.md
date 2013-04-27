# TwigPress #

## What is TwigPress? ##
TwigPress is a Wordpress theme which is based upon Twig Engine. Probably it could be flagged also as "another Twig Engine Theme for Wordpress".

## So, why you think it is special? ##
TwigPress was born with ease in mind, cleaner APIs and ease to extended. Well, kind-of for now :)

## How does it work? ##
Basically you only have to work inside "templates" directory. You do not have to touch anything at all.
If you need to enable/disable Debug function on Twig you just have to change `TwigPress_Debug` define to `true` or `false`.
By default it will always be enabled. Keep in mind that if you enable it you'll have cache disabled, if enabled, cache will be enabled.

## API ##
In fact there's not much to explain. When you want to run TwigPress in your template file you just have to do something like

<pre>
&lt;?php
  new TwigPress(array(/* your arguments here */));
?&gt;
</pre>

and the arguments you can use until now are:

### 0.1 ###
* `vars`: your custom template variables you could use on your .twig templates within the `tplapi` keyword.

### Examples ###
<pre>
&lt;?php
    new TwigPress(array(
        "vars" =&gt; array(
            "myname" =&gt; "Author name",
            "mysurname" =&gt; "Author surname"
        )
    ));
?&gt;
</pre>

## Template API ##
TwigPress comes with three main API keywords:

### 0.1 ###
* `wpapi` this is the most wonderful API which TwigPress comes in mind with. It is the entry point to access Wordpress Core APIs. So any `wpapi.your_api_call_here` is exactly you was used to do with templates until now.

* `twpapi` this is the main TwigPress API entry point of the next future releases. For now you'll have just these APIs:
    * `base_uri` this is the base URL which points to your theme installation inside the "templates/" directory.
    * `template_name` this is the current template name which Wordpress required.
    * `fn` this will be the entry point to custom function of TwigPress. Until now they are:
        * `addCss` this is a custom function to enqueue your CSS in Wordpress. Useful for other plugins if you want let them to compact your CSS for example.
        * `addJs` same as `addCss` but this time for Javascript files.

* `tplapi` this will be your custom vars that you'll pass to TwigPress when you initialize your Class.

### Examples ###
<pre>
{# Wordpress API call #}
Wordpress Name: {{ wpapi.get_bloginfo('name') }}
Wordpress Description: {{ wpapi.get_bloginfo('description') }}

{# TwigPress API call #}
Current template: {{twpapi.template_name }}

{# Template API call #}
My name: {{ tplapi.myname }}
My Surname: {{ tplapi.mysurname }}
</pre>

## License ##
This project is covered up by **GPLv3** license. You can find it attached to any .php file and the LICENSE file itself.
Please keep it open as much as you can :)