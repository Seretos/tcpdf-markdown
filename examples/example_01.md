- - -

* item1
  > quoted
* item2
 

> **bold** *italic*

* item 1
* 
* item 3

*\*\*italic***
***bold** italic*


*\***italic s***
~~test~~
\~~test~~

Headlines
=
# Headline 2
---
# Headline 1
## Headline 2
### Headline 3
#### Headline 4
##### Headline 5
###### Headline 6

Dividers
===
---
***
___

Text formats
=
**bold**
*italic*
\*no italic\*
***bold and italic***
__bold__
_italic_
\*not italic*
**bold *italic* bold**
~~strikethrough~~

Links
=
[# Linktext](https://github.com "optional Title")
[**Linktext2**](https://google.com)
[link][id]
[*google*][id_2]
https://google.de

[id]: https://example.com "test"
[id_2]: https://www.google.de
[id_3]: tmp

Text blocks
=
aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa

Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.

Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse molestie consequat, vel illum dolore eu feugiat nulla facilisis at vero eros et accumsan et iusto odio dignissim qui blandit praesent luptatum zzril delenit augue duis dolore te feugait nulla facilisi. Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat.

Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip ex ea commodo consequat. Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse molestie consequat, vel illum dolore eu feugiat nulla facilisis at vero eros et accumsan et iusto odio dignissim qui blandit praesent luptatum zzril delenit augue duis dolore te feugait nulla facilisi.

Nam liber tempor cum soluta nobis eleifend option congue nihil imperdiet doming id quod mazim placerat facer possim assum. Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip ex ea commodo consequat.

Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse molestie consequat, vel illum dolore eu feugiat nulla facilisis.

At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, At accusam aliquyam diam diam dolore dolores duo eirmod eos erat, et nonumy sed tempor et et invidunt justo labore Stet clita ea et gubergren, kasd magna no rebum. sanctus sea sed takimata ut vero voluptua. est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam

Lists
=
Unsorted list
-

- test1
 - test 2
    - test3
        - test4
            -  test5
    - test 6


  *  test
   * test3
* test2

* test1
  * test2
  *    test3
      * test4 
* test 5
test 6

+ test1
  + test2
    + test3
+ test4

* test 1
  + test 2
    - test 3


- [ ] test
    -   [x] test
      -[x] ss
- [x] test

Sorted list
-
1. test1
   1. test 2
   2. test 3
 3. test 4
    4. test 5
      5. test 6
         6. test 7
            7. test 8
               8. test 9
                  9. test 10
                     10. test 11
                         11.   test 12


1. [ ] test
   2. [x] test
      3.[x] ss
4. [x] test


Tables
=

| header 1 | header 2 | header 3 | header 4 |
|-|-|-|-|
| content 1 | content 2 | content 3 | content 4 |
| content 5 | content 6 | content 7 | content 8|
| **bold1** | *italic1* | **bold2** | *italic2* |
| **mixed***italic* | **mixed***italic* | **mixed***italic* | **mixed***italic* |

| left | center | right |
|:-----|:------:|------:|
| a    |   b    |    c  |

Quotes
=
    a
    b
    c
    d
        e
    >   f
        > g

    h

    > a
    
    i
    [test](https://www.google.de)
    aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa

> aa
   >     a
> 
>     > aa
> 
> >  bb
> > > >     cc

> **bold** *italic*

```php
private function method(string $test): void {
    print 'hello ' . $test;
}
```
```
private function method(string $test): void {
    print 'hello ' . $test;
}
```

this is `   inline code`
`tes
s
ss`
this is`another test`
**bold `inline code`**

> ```
> nested code
> ```

    ```
    not nested
    ```


Images
=
<img src='https://gnulinux.ch/bl-content/uploads/pages/5f167519355a319691ee34fe44cdae21/markdown1.png' width="300" height="200">

[![pipeline status](https://gitlab.com/seredos/application-bot/badges/main/pipeline.svg)](https://gitlab.com/seredos/application-bot/-/commits/main)
![pipeline status](https://gitlab.com/seredos/application-bot/badges/main/pipeline.svg)

[![pipeline status](https://gnulinux.ch/bl-content/uploads/pages/5f167519355a319691ee34fe44cdae21/markdown1.png)](https://gitlab.com/seredos/application-bot/-/commits/main)

[reference_img]: https://gnulinux.ch/bl-content/uploads/pages/5f167519355a319691ee34fe44cdae21/markdown1.png
![pipeline status][reference_img]

Definition lists
=

test
: test1
     :   test2

test2
: test3
: test4