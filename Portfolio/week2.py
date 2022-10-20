# greeting
# name = input("What is your name? ")
# print(f"Hello, {name}")


# convert centigrade to fahrenheit
# print((float(input("Enter temperature in C: ")) * 9.0 / 5.0) + 32)


# student group
def group(students=None, grp_size=None):
    if students % grp_size == 1:
        print(
            f"""There will be {str(students // grp_size)} groups with {str(students % grp_size)} student left over""")
    else:
        print(
            f"""There will be {str(students // grp_size)} groups with {str(students % grp_size)} students left over""")


# group(int(input("Enter number of students: ")), int(input("Enter group size: ")))


# sweets
def sweets(students=None, sweets=None):
    if sweets % students == 1:
        print(
            f"""Each student will have {str(sweets // students)} sweets with {str(sweets % students)} sweet left over""")
    else:
        print(
            f"""Each student will have {str(sweets // students)} sweets with {str(sweets % students)} sweets left over""")


# sweets(int(input("Enter number of students: ")), int(input("Enter how much candy: ")))
