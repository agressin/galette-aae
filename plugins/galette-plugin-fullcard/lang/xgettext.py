#!/usr/bin/python
"""
/* xgettext.py
 *
 * replace xgettext -k_T -n 
 * support string like value="{_T("xxxx")}"
 * generates message.po with the same symtax as regular xgettext
 * translatable string sort may differ from regular xgettext
 *
 * - Identification
 * Copyright (c) 2005 Didier CHEVALIER
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 *
 */
"""
import sys
import re

# pattern definition
translatable= re.compile("_T\((\"[^\"]*\")\)")
tpl_translatable= re.compile("_T\ string=(\"[^\"]*\")")

# constants string
startLoc = "#: "
nextLoc  = " "

#
dico = {}

def location() :
   return inputFileName + ":" + str(lineNum+1)

#
for inputFileName in sys.argv[1:] :
   inFile=open(inputFileName)
   lines = inFile.readlines()
   inFile.close()
   # get line
   for lineNum, line in enumerate(lines) :
      # search translatable strings
      matchs =  translatable.findall(line)
      for match in matchs:
          if dico.has_key(match):
            if dico[match][-1:] == "\n":
              dico[match] += startLoc + location()
            else :
              dico[match] += nextLoc + location() + "\n"
          else :
            dico[match] = startLoc + location()
      tpl_matchs =  tpl_translatable.findall(line)
      for tpl_match in tpl_matchs:
          if dico.has_key(tpl_match):
            if dico[tpl_match][-1:] == "\n":
              dico[tpl_match] += startLoc + location()
            else :
              dico[tpl_match] += nextLoc + location() + "\n"
          else :
            dico[tpl_match] = startLoc + location()

#
outFile = open("messages.po",'w')
for k, v in dico.iteritems():
   outFile.write(v)
   if v[-1:] != "\n" :
     outFile.write("\n")
   outFile.write("msgid " + k + "\nmsgstr \"\"\n\n")
outFile.close()
