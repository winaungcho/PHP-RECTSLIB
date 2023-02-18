# PHP-RECTSLIB
This class Lib help you to construct the rectangles using various logical operations such as Union, Intersect abd Subtract.

## Algorythm
Algorythm is very simple.
- A region on which contains all the rectangles is divided into several rectangles according to the corners of the rectangles.
- Remove the rectangles above which does not lie inside all rectangles. This step gives the non-overlapped union of the rectangles.
- Then choose or remove the rectangles which lies inside the subject rectangles according to boolean operation union, intersect or subtract.

## Operation procedure
### Create rectangles
Rectangles are represenred by the associative array in which diagonal points p1 and p2 are stored. Other variables for drawing are name, color and state. Point p1 is always upper left corner and p2 is for lower right of rectangles so that p1 has smaller value of x and y then p2.
### Merge all rectangles into mesh
List all x and y coordinates from the corner points p1, p2 of rectangles. Sort x and y list and then create rectangles for all x and y. Get (n-1)^2 mesh rectangles for n original rectangles.
### Generate rectangle operation
Select mesh by checking inside or not the desired rectangles which for logical operation.
Eg. 
If A B C D E are the recangles, 
- For unioun of [A B C] & [D E], mesh must be lied in side both of [A B C] & [D E].
- For subtract [D E] from [A B C D], mesh must be inside the [A B C D] and outside of [D E].
- For intersect [A B C] & [D E], mesh must bbe inside of both [A B C] & [D E].

## Check with Images
### Original Rectangkes

- A-GREEN-{10,10:300,200}
- B-RED-{40,30:200,300}
- C-ORANGE-{150,40:400,150}
- D-BLUE-{140,320:200,400}
- E-BROWN-{80,160:250,250}

![PHP-RECTSLIB](images/rectsorigin.png)

### Start meshing for all rectangles
![PHP-RECTSLIB](images/rectsuniversal.png)

### Meshing and get union of rectangles
Union of [A B] & [C D]
![PHP-RECTSLIB](images/rectsunion.png)

### Intersection of all with 2 rectangles
Intersect of [A B C D] & [B E]
![PHP-RECTSLIB](images/rectsintersect.png)

### Subtract of 2 from all
Subtract [B E] from [A B C D]
![PHP-RECTSLIB](images/rectssubtract.png)

## Contact
Contact me for comercial use via mail winaungcho@gmail.com.


