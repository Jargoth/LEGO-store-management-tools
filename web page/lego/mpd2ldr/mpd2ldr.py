#!/usr/bin/python

import sys, getopt, os, array

def printusage():
    print 'Usage:'
    print ' mpd2ldr.py [options] <inputfile>'
    print 'Options:'
    print ' -o <outputfile>   ; Output file name - default is inputfile .ldr'
    print ' -m <max levels>   ; How many iterations - default is 99'
    print ' -k                ; Keep subfiles [then its still an MPD file!]'

def main(argv):
    keep_orig = False
    inputfile = ''
    outputfile = ''
    maxlevels = 99

    try:
        opts, args = getopt.getopt(argv,"hi:o:m:k",["ifile=","ofile=","maxlevels="])
    except getopt.GetoptError:
        printusage()
        sys.exit(2)
    for opt, arg in opts:
        if opt == '-h':
            printusage()
            sys.exit()
        elif opt in ("-o", "--ofile"):
            outputfile = arg
        elif opt in ("-m", "--maxlevels"):
            maxlevels = int(arg)
            if maxlevels == 0:
                print 'WARNING: illegal value for -m/maxlevels: ', arg
        elif opt in ("-k"):
            keep_orig = True

    inputfile = argv[len(argv)-1]

    if len(outputfile) == 0:
        outputfile = inputfile[:-4] + '.ldr'

    if os.path.isfile(inputfile) is False:
        print 'Input file not found'
        sys.exit()

    if outputfile.endswith('.ldr') == False:
        if outputfile.endswith('.mpd') == False:
            print 'WARNING, outputfile', outputfile, 'does not have ldr or mpd extension'

    f_mpd = open(inputfile, 'r')

    mpd_lines = [line.rstrip('\n') for line in f_mpd]
    main_lines = []
    rest_lines = []
    ldr_lines = []
    sub_lines = [[]]
    fileindex = 0;
    iterations = 0;
    lineno=0;
    subfile_found = True;

    # READ input file and populate data structures
    for line in mpd_lines:
        elements = line.split()

        if lineno and (len(line.split()) > 1):
            if (line.split()[0] == '0') and (line.split()[1] == 'FILE'):
                sub_lines.append([])
                index = 2
                subname = ''
                while (index < len(elements)):
                    subname = subname + elements[index]
                    index = index+1

                sub_lines[fileindex].append(subname)

                fileindex = fileindex+1
                lineno=0

        if (fileindex==0):
            main_lines.append(line)
        else:
            rest_lines.append(line)
            sub_lines[fileindex-1].append(line)

        lineno = lineno+1

    subfiles = fileindex-1

    # Now iteratively process file, one level per iteration (nested ldr includes require additional iterations)
    while subfile_found:
        subfile_found = False

        for mline in main_lines:
            elements = mline.split()

            if (len(elements) >= 15) and (elements[0] == '1'):
                index = 14
                filename = ''

                while (index < len(elements)):
                    filename = filename + elements[index]
                    index = index+1

                if (elements[index-1]).endswith('ldr'):
                    subfile_found = True;
                    header = '0 From ' + filename
                    ldr_lines.append(header)
                    index=0
                    while (index <= subfiles):
                        if (sub_lines[index][0] == filename):
                            break;
                        index = index+1

                    if (index > subfiles):
                        print 'WARNING: SUBMODULE NOT FOUND: ', filename
                    else:
                        # HERE'S WHERE THE MEAT IS: Process Subfile and Transform
                        #1 Determine transformation matrix
                        X = [[0,0,0,0],
                             [0,0,0,0],
                             [0,0,0,0],
                             [0,0,0,0]]

                        color = elements[1]

                        X[0][0] = float(elements[5])
                        X[0][1] = float(elements[6])
                        X[0][2] = float(elements[7])
                        X[0][3] = float(elements[2])  # x
                        X[1][0] = float(elements[8])
                        X[1][1] = float(elements[9])
                        X[1][2] = float(elements[10])
                        X[1][3] = float(elements[3])  # y
                        X[2][0] = float(elements[11])
                        X[2][1] = float(elements[12])
                        X[2][2] = float(elements[13])
                        X[2][3] = float(elements[4])  # z
                        X[3][0] = 0
                        X[3][1] = 0
                        X[3][2] = 0
                        X[3][3] = 1

                        newfile = 1
                        for line in sub_lines[index]:
                            elements = line.split()

                            if newfile:
                                if (len(elements) > 0) and (elements[0] == '1'):
                                    newfile = 0
                                else:
                                    continue

                            if (len(elements) < 1):
                                continue

                            if (elements[0] == '0'):
                                if (len(elements) > 1) and (elements[1] != 'FILE'):
                                    ldr_lines.append(line)

                            elif (elements[0] != '1'):
                                print('WARNING: Unsupported line:')
                                print(line)
                                ldr_lines.append(line)

                            else:

                                # HERE'S WHERE THE MEAT IS: Process Subfile and Transform
                                if (len(elements) >= 15) and (elements[0] == '1'):
                                    Y = [[0,0,0,0],
                                         [0,0,0,0],
                                         [0,0,0,0],
                                         [0,0,0,0]]
                                    subcolor = elements[1]
                                    
                                    Y[0][0] = float(elements[5])
                                    Y[0][1] = float(elements[6])
                                    Y[0][2] = float(elements[7])
                                    Y[0][3] = float(elements[2])  # x
                                    Y[1][0] = float(elements[8])
                                    Y[1][1] = float(elements[9])
                                    Y[1][2] = float(elements[10])
                                    Y[1][3] = float(elements[3])  # y
                                    Y[2][0] = float(elements[11])
                                    Y[2][1] = float(elements[12])
                                    Y[2][2] = float(elements[13])
                                    Y[2][3] = float(elements[4])  # z
                                    Y[3][0] = 0
                                    Y[3][1] = 0
                                    Y[3][2] = 0
                                    Y[3][3] = 1

                                    result = [[0,0,0,0],
                                              [0,0,0,0],
                                              [0,0,0,0],
                                              [0,0,0,0]]

                                    
                                    # Matrix Multiplication:
                                    for i in range(len(X)):
                                        for j in range(len(Y[0])):
                                            for k in range(len(Y)):
                                                result[i][j] += X[i][k] * Y[k][j]

                                    tline = elements[0]
                                    if (subcolor == '16'):
                                        # replace default body color with color in parent file
                                        tline = tline + ' ' + color
                                    else:
                                        tline = tline + ' ' + subcolor

                                    for i in range(3):
                                        tline = tline + ' ' + str(result[i][3])
                                        if (tline.endswith('.0')):
                                            tline = tline[:-2]

                                    for i in range(3):
                                        for j in range(3):
                                            tline = tline + ' ' + str(result[i][j])
                                            if (tline.endswith('.0')):
                                                tline = tline[:-2]

                                    i = 14
                                    while (i < len(elements)):
                                        tline = tline + ' ' + elements[i]
                                        i = i+1

                                    ldr_lines.append(tline);
                else:
                    # REGULAR '1' LINE: Copy It
                    ldr_lines.append(mline);

            else:
                # REGULAR non-'1' LINE: Copy It Too
                ldr_lines.append(mline)
        #finished processing one iteration

        if (subfile_found):
            iterations = iterations + 1

            if (iterations >= maxlevels):
                break
            
            # get ready for another iteration
            main_lines = ldr_lines
            ldr_lines = []
            
    #end of main loop

    f_ldr = open(outputfile, 'wb')

    for line in ldr_lines:
        outline = line + '\n'
        f_ldr.write(outline)

    if (keep_orig or subfile_found):
        f_ldr.write('\n0 Original LDR subfiles:\n\n')

        for line in rest_lines:
            outline = line + '\n'
            f_ldr.write(outline)

    print "Created file ", outputfile, ", removed ", iterations, " levels of LDR nesting!"
        
if __name__ == "__main__":
   main(sys.argv[1:])
