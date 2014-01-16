ElendevWidgetBundle
===================

The ElendevWidgetBundle allow to add widgets (independent HTML code) into existing templates withouth dependencies.

Installation
------------
The ElendevWidgetBundle is available through composer.
You just have to add elendev/widget-bundle into your composer.json.

Configuration
-------------
	elendev_widget:
    	enable_annotations:   true # false : disable annotation support
    	scan_services:        true # false : don't check annotations on services
    	scan_widget_directory:  true # false : don't scan Widget directory
    	widget_directory:     'Widget' # name of the widget directory at bundle's root

Register a new widget
---------------------
A widget is a service method returning simple HTML code.
You only have to tag your service method with :

    tags:
            - {name: elendev.widget, tag: your_tagname, method: method_name, priority: optional_integer_priority}

Annotations are also available, you can annotate any method of a service or of a class in `widget_directory` sub-directory.

	namespace Acme\DemoBundle\Widget;
	use Elendev\WidgetBundle\Annotation\Widget;
	
	class MyTestWidget {
		/**
	 	* @Widget(tag="main", priority=99)
	 	*/
		public function myYoupiWidget(){
			return "This is a simple widget";
		}
	}

The value of widget is by default the tag value.

Use Twig's extension widget method
---------------------------------

To include widgets in a view you only have to call this method :

`{{ widgets('your_tagname') }}`

You can add multiple widgets emplacements in your project, specifieds by their `tagname`.

Some widget's emplacements can provide parameters. You can add as much parameters as you need to `widgets` call, they will be passed to the widget.


    #services.yml
    services:
        my_service:
            class: Some/Class/MyWidgets
                tags:
                    - {name: elendev.widget, method: memberDatas, tag: member_profile}
---
    {# Template twig #}
    {{ widgets('member_profile', member) }}
---

    //PHP code
    class MyWidgets{
        public function memberDatas(Member $member){
            return 'some templates';
        }
    }